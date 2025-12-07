<?php

namespace App\Helpers;

class ScoreHelper
{
    /**
     * Calculate score_rank based on rank tier
     * 
     * @param string $rank
     * @return int 1-7
     */
    public static function calculateRankScore($rank)
    {
        $rank = strtolower(trim($rank));

        // Warrior - GM = 1
        if (
            str_contains($rank, 'warrior') || str_contains($rank, 'grandmaster') ||
            str_contains($rank, 'master') || str_contains($rank, 'gm')
        ) {
            return 1;
        }

        // Epic - Legend = 2
        if (str_contains($rank, 'epic') || str_contains($rank, 'legend')) {
            return 2;
        }

        // Mythic = 3
        if (str_contains($rank, 'mythic') && !str_contains($rank, 'honor') && !str_contains($rank, 'glory')) {
            return 3;
        }

        // Mythical Honor = 4
        if (str_contains($rank, 'honor')) {
            return 4;
        }

        // Mythical Glory = 5
        if (str_contains($rank, 'glory')) {
            // Extract bintang count if exists
            preg_match('/\d+/', $rank, $matches);
            $stars = isset($matches[0]) ? (int)$matches[0] : 0;

            if ($stars < 500) {
                return 5; // Glory
            } else {
                return 7; // Immortal > 500
            }
        }

        // Immortal < 500 = 6
        if (str_contains($rank, 'immortal')) {
            preg_match('/\d+/', $rank, $matches);
            $stars = isset($matches[0]) ? (int)$matches[0] : 0;

            if ($stars < 500) {
                return 6;
            } else {
                return 7;
            }
        }

        // Default
        return 2;
    }

    /**
     * Calculate score_koleksi based on collector tier
     * 
     * @param string $koleksi
     * @return int 1-36
     */
    public static function calculateKoleksiScore($koleksi)
    {
        if (empty($koleksi) || $koleksi === 'Unknown') {
            return 0;
        }

        $koleksi = strtolower(trim($koleksi));

        // Extract tier number if exists (e.g., "Kolektor Ternama 4" -> 4)
        preg_match('/\d+/', $koleksi, $matches);
        $tier = isset($matches[0]) ? (int)$matches[0] : 1;

        // Kolektor Amatir 1-5 = 1-5
        if (str_contains($koleksi, 'amatir')) {
            return min($tier, 5);
        }

        // Kolektor Junior 1-5 = 6-10
        if (str_contains($koleksi, 'junior')) {
            return 5 + min($tier, 5);
        }

        // Kolektor Senior 1-5 = 11-15
        if (str_contains($koleksi, 'senior')) {
            return 10 + min($tier, 5);
        }

        // Kolektor Ahli 1-5 = 16-20
        if (str_contains($koleksi, 'ahli')) {
            return 15 + min($tier, 5);
        }

        // Kolektor Ternama 1-5 = 21-25
        if (str_contains($koleksi, 'ternama')) {
            return 20 + min($tier, 5);
        }

        // Kolektor Terhormat 1-5 = 26-30
        if (str_contains($koleksi, 'terhormat')) {
            return 25 + min($tier, 5);
        }

        // Kolektor Juragan 1-5 = 31-35
        if (str_contains($koleksi, 'juragan')) {
            return 30 + min($tier, 5);
        }

        // Kolektor Sultan = 36
        if (str_contains($koleksi, 'sultan')) {
            return 36;
        }

        // Default (unknown collector)
        return 10;
    }

    /**
     * Calculate score_winrate based on winrate percentage
     * 
     * @param float $winrate
     * @return int 1-6
     */
    public static function calculateWinrateScore($winrate)
    {
        if ($winrate < 45) {
            return 1; // < 45%
        } elseif ($winrate < 50) {
            return 2; // 45-50%
        } elseif ($winrate < 55) {
            return 3; // 51-55%
        } elseif ($winrate < 60) {
            return 4; // 56-60%
        } elseif ($winrate < 70) {
            return 5; // 61-70%
        } else {
            return 6; // > 70%
        }
    }

    /**
     * Calculate score_total_match based on total matches
     * 
     * @param int $totalMatch
     * @return int 1-6
     */
    public static function calculateTotalMatchScore($totalMatch)
    {
        if ($totalMatch < 1500) {
            return 6; // < 1500
        } elseif ($totalMatch < 2000) {
            return 5; // 1500-2000
        } elseif ($totalMatch < 3000) {
            return 4; // 2000-3000
        } elseif ($totalMatch < 4000) {
            return 3; // 3000-4000
        } elseif ($totalMatch < 5000) {
            return 2; // 4000-5000
        } else {
            return 1; // > 5000
        }
    }

    /**
     * Calculate all scores at once
     * 
     * @param array $data
     * @return array
     */
    public static function calculateAllScores($data)
    {
        return [
            'score_rank' => self::calculateRankScore($data['rank'] ?? ''),
            'score_koleksi' => self::calculateKoleksiScore($data['koleksi'] ?? ''),
            'score_winrate' => self::calculateWinrateScore($data['winrate'] ?? 50),
            'score_total_match' => self::calculateTotalMatchScore($data['total_match'] ?? 0),
        ];
    }
}
