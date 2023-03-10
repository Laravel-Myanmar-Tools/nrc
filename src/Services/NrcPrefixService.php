<?php

namespace LaravelMyanmarTools\Nrc\Services;

use LaravelMyanmarTools\Nrc\Exceptions\InvalidNrc;

/**
 * credit: NRCPrefix
 * original: https://github.com/libstdmmr/NRCPrefix/blob/master/index.js
 */
class NrcPrefixService
{
    protected $nrc;

    const EN_CHARS = ['KA', 'KH', 'GA', 'GH', 'NG', 'CA', 'CH', 'JA', 'JH', 'NY', 'DD', 'NN', 'TA', 'TH', 'DA', 'DH', 'NA', 'PA', 'PH', 'BA', 'BH', 'MA', 'YA', 'RA', 'LA', 'WA', 'SA', 'HA', 'LL', 'AH', 'OU', 'AE'];

    const MM_CHARS = ['က', 'ခ', 'ဂ', 'ဃ', 'င', 'စ', 'ဆ', 'ဇ', 'ဈ', 'ည', 'ဎ', 'ဏ', 'တ', 'ထ', 'ဒ', 'ဓ', 'န', 'ပ', 'ဖ', 'ဗ', 'ဘ', 'မ', 'ယ', 'ရ', 'လ', 'ဝ', 'သ', 'ဟ', 'ဠ', 'အ', 'ဥ', 'ဧ'];

    const EN_NUMS = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    const MM_NUMS = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];

    const EN_TYPES = ['N'];

    const MM_TYPES = ['နိုင်'];

    const NAING = 'NAING';

    const N = 'N';

    const EN_TO_MM = 'en-to-mm';

    const MM_TO_EN = 'mm-to-en';

    const STATES = [
        [
            'en' => 'Kachin',
            'mm' => 'ကချင်ပြည်နယ်',
        ],
        [
            'en' => 'Kayah',
            'mm' => 'ကယားပြည်နယ်',
        ],
        [
            'en' => 'Kayin',
            'mm' => 'ကရင်ပြည်နယ်',
        ],
        [
            'en' => 'Chin',
            'mm' => 'ချင်းပြည်နယ်',
        ],
        [
            'en' => 'Sagaing',
            'mm' => 'စစ်ကိုင်းတိုင်း',
        ],
        [
            'en' => 'Tanintharyi',
            'mm' => 'တနင်္သာရီတိုင်း',
        ],
        [
            'en' => 'Bago',
            'mm' => 'ပဲခူးတိုင်း',
        ],
        [
            'en' => 'Magway',
            'mm' => 'မကွေးတိုင်း',
        ],
        [
            'en' => 'Mandalay',
            'mm' => 'မန္တလေးတိုင်း',
        ],
        [
            'en' => 'Mon',
            'mm' => 'မွန်ပြည်နယ်',
        ],
        [
            'en' => 'Rakhine',
            'mm' => 'ရခိုင်ပြည်နယ်',
        ],
        [
            'en' => 'Yangon',
            'mm' => 'ရန်ကုန်တိုင်း',
        ],
        [
            'en' => 'Shan',
            'mm' => 'ရှမ်းပြည်နယ်',
        ],
        [
            'en' => 'Ayeyarwaddy',
            'mm' => 'ဧရာဝတီတိုင်း',
        ],
    ];

    public function __construct(string $nrc)
    {
        $this->nrc = $this->convert($nrc, static::MM_TO_EN);
    }

    public function isNrc(): bool
    {
        // preg_match('/^((?:' . implode('|', static::MM_NUMS) . '){1,2})\/(?:' . implode('|', static::MM_CHARS) . '){3}\((?:နိုင်)\)((?:' . implode('|', static::MM_NUMS) . '){6})$/', $this->nrc); // for mm
        return preg_match('/^([\d]{1,2})\/(?:'.implode('|', static::EN_CHARS).'){3}\((?:N|NAING)\)([\d]{6})$/', $this->nrc);
    }

    public function normalizeNrc(string $lang = 'en'): string
    {
        $this->nrc = str_replace('-', '', trim($this->nrc));
        if ($this->isNrc()) {
            if ($lang == 'mm') {
                return $this->convert($this->nrc, static::EN_TO_MM);
            }

            return $this->nrc;
        }
        throw new InvalidNrc('Invalid NRC');
    }

    private function convert(string $nrc, string $type): string
    {
        $nrc = str_replace(static::NAING, static::N, strtoupper($nrc));
        $en = array_merge(static::EN_TYPES, static::EN_NUMS, static::EN_CHARS);
        $mm = array_merge(static::MM_TYPES, static::MM_NUMS, static::MM_CHARS);
        if ($type == static::EN_TO_MM) {
            $en = array_reverse($en);
            $mm = array_reverse($mm);

            return str_replace($en, $mm, $nrc);
        }

        return str_replace($mm, $en, $nrc);
    }
}
