<?php

namespace App\Controllers;

class AHP extends BaseController
{
    // -------------------------------------------------------
    // HALAMAN WIZARD
    // -------------------------------------------------------
    public function index(): string
    {
        return view('ahp/index');
    }

    // -------------------------------------------------------
    // API: Hitung AHP (POST JSON)
    // -------------------------------------------------------
    public function calculate()
    {
        // Ambil body JSON
        $input = $this->request->getJSON(true);

        if (!$input) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON(['error' => 'Input tidak valid.']);
        }

        $criteriaNames = array_values($input['criteria']      ?? []);
        $altNames      = array_values($input['alternatives']  ?? []);
        $matrixRaw     = $input['criteriaMatrix']             ?? [];   // n×n
        $altScoresRaw  = $input['altScores']                  ?? [];   // n×m

        $n = count($criteriaNames);
        $m = count($altNames);

        // Validasi minimal
        if ($n < 2 || $m < 2) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON(['error' => 'Minimal 2 kriteria dan 2 alternatif diperlukan.']);
        }

        // Sanitasi nilai matriks
        $matrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $v = floatval($matrixRaw[$i][$j] ?? 1);
                $matrix[$i][$j] = ($v > 0) ? $v : 1;
            }
        }

        // Sanitasi skor alternatif
        $altScores = [];
        for ($c = 0; $c < $n; $c++) {
            for ($a = 0; $a < $m; $a++) {
                $v = floatval($altScoresRaw[$c][$a] ?? 1);
                $altScores[$c][$a] = ($v > 0) ? $v : 1;
            }
        }

        $result = $this->doCalculate($criteriaNames, $altNames, $matrix, $altScores);
        return $this->response->setJSON($result);
    }

    // -------------------------------------------------------
    // KALKULASI AHP
    // -------------------------------------------------------
    private function doCalculate(
        array $criteriaNames,
        array $altNames,
        array $matrix,
        array $altScores
    ): array {
        $n = count($criteriaNames);
        $m = count($altNames);

        // ── STEP 1: Jumlah kolom ──────────────────────────────
        $colSums = array_fill(0, $n, 0.0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $colSums[$j] += $matrix[$i][$j];
            }
        }

        // ── STEP 2: Normalisasi matriks kriteria ──────────────
        $normalMatrix = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalMatrix[$i][$j] = $matrix[$i][$j] / $colSums[$j];
            }
        }

        // ── STEP 3: Priority vector (rata-rata baris) ─────────
        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $weights[$i] = array_sum($normalMatrix[$i]) / $n;
        }

        // ── STEP 4: Uji Konsistensi ───────────────────────────
        // Weighted Sum Vector
        $wsv = array_fill(0, $n, 0.0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $wsv[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }

        // Lambda max
        $lambdaMax = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $lambdaMax += ($weights[$i] > 0) ? ($wsv[$i] / $weights[$i]) : 0;
        }
        $lambdaMax /= $n;

        $ci = ($n > 1) ? ($lambdaMax - $n) / ($n - 1) : 0.0;
        $riTable = [0=>0, 1=>0, 2=>0, 3=>0.58, 4=>0.90, 5=>1.12,
                    6=>1.24, 7=>1.32, 8=>1.41, 9=>1.45, 10=>1.49];
        $ri = $riTable[min($n, 10)];
        $cr = ($ri > 0) ? $ci / $ri : 0.0;

        // ── STEP 5: Normalisasi skor alternatif per kriteria ──
        $altNormal  = [];
        $altWeights = [];
        for ($c = 0; $c < $n; $c++) {
            $total = array_sum($altScores[$c]);
            for ($a = 0; $a < $m; $a++) {
                $altNormal[$c][$a]  = ($total > 0) ? ($altScores[$c][$a] / $total) : 0;
                $altWeights[$c][$a] = $altNormal[$c][$a];
            }
        }

        // ── STEP 6: Skor akhir ────────────────────────────────
        $finalScores = array_fill(0, $m, 0.0);
        for ($a = 0; $a < $m; $a++) {
            for ($c = 0; $c < $n; $c++) {
                $finalScores[$a] += $weights[$c] * $altWeights[$c][$a];
            }
        }

        // Ranking
        $sortedIdx = array_keys($finalScores);
        usort($sortedIdx, fn($x, $y) => $finalScores[$y] <=> $finalScores[$x]);
        $ranking = array_fill(0, $m, 0);
        foreach ($sortedIdx as $rank => $idx) {
            $ranking[$idx] = $rank + 1;
        }

        return [
            'n'             => $n,
            'm'             => $m,
            'criteriaNames' => $criteriaNames,
            'altNames'      => $altNames,
            'matrix'        => $matrix,
            'colSums'       => $colSums,
            'normalMatrix'  => $normalMatrix,
            'weights'       => $weights,
            'wsv'           => $wsv,
            'lambdaMax'     => $lambdaMax,
            'ci'            => $ci,
            'ri'            => $ri,
            'cr'            => $cr,
            'consistent'    => ($cr <= 0.1),
            'altScores'     => $altScores,
            'altNormal'     => $altNormal,
            'altWeights'    => $altWeights,
            'finalScores'   => $finalScores,
            'ranking'       => $ranking,
        ];
    }
}
