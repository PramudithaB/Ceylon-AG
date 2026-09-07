<?php

namespace App\Support;

class Locations
{
    /**
     * Map of Provinces to Districts in Sri Lanka.
     */
    public const PROVINCES_AND_DISTRICTS = [
        'Western' => [
            'Colombo',
            'Gampaha',
            'Kalutara',
        ],
        'Central' => [
            'Kandy',
            'Matale',
            'Nuwara Eliya',
        ],
        'Southern' => [
            'Galle',
            'Matara',
            'Hambantota',
        ],
        'Northern' => [
            'Jaffna',
            'Kilinochchi',
            'Mannar',
            'Vavuniya',
            'Mullaitivu',
        ],
        'Eastern' => [
            'Batticaloa',
            'Ampara',
            'Trincomalee',
        ],
        'North Western' => [
            'Kurunegala',
            'Puttalam',
        ],
        'North Central' => [
            'Anuradhapura',
            'Polonnaruwa',
        ],
        'Uva' => [
            'Badulla',
            'Moneragala',
        ],
        'Sabaragamuwa' => [
            'Ratnapura',
            'Kegalle',
        ],
    ];

    /**
     * Get list of all province names.
     */
    public static function getProvinces(): array
    {
        return array_keys(self::PROVINCES_AND_DISTRICTS);
    }

    /**
     * Normalize province name (e.g. "Western Province" -> "Western").
     */
    public static function normalizeProvince(?string $province): ?string
    {
        if (! $province) {
            return null;
        }

        $clean = trim(preg_replace('/(\s+Province|\s+Prov\.)/i', '', $province));

        foreach (array_keys(self::PROVINCES_AND_DISTRICTS) as $name) {
            if (strcasecmp($name, $clean) === 0) {
                return $name;
            }
        }

        return $clean;
    }

    /**
     * Get districts belonging to a specific province.
     */
    public static function getDistrictsByProvince(?string $province): array
    {
        $normalized = self::normalizeProvince($province);

        return self::PROVINCES_AND_DISTRICTS[$normalized] ?? [];
    }

    /**
     * Get all 25 districts flat list.
     */
    public static function getAllDistricts(): array
    {
        $all = [];
        foreach (self::PROVINCES_AND_DISTRICTS as $districts) {
            $all = array_merge($all, $districts);
        }

        return array_values(array_unique($all));
    }

    /**
     * Check if a given district belongs to the given province.
     */
    public static function isValidPair(?string $province, ?string $district): bool
    {
        if (! $province || ! $district) {
            return false;
        }

        $validDistricts = self::getDistrictsByProvince($province);

        foreach ($validDistricts as $valid) {
            if (strcasecmp($valid, trim($district)) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get hierarchy as an associative array for JSON rendering.
     */
    public static function getHierarchy(): array
    {
        return self::PROVINCES_AND_DISTRICTS;
    }
}
