<?php
/**
 * Date: 13.11.2020
 * @author Timur Kasumov (XAKEPEHOK)
 */

namespace XAKEPEHOK\ArrayWildcardExplainer;


class ArrayWildcardExplainer
{
    public static function explainOne(array $data, string $key, bool $inverse = false): array
    {
        $result = self::explain($data, $key, $inverse);
        sort($result);
        return array_values($result);
    }

    public static function explainMany(array $data, array $keys, bool $inverse = false): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result = array_merge($result, self::explain($data, $key, $inverse));
        }

        sort($result);
        return array_values($result);
    }

    private static function explain(array $data, string $key, bool $inverse = false): array
    {
        $flat = array_keys(self::flatten($data));

        $extended = [];
        foreach ($flat as $path) {
            $extended[$path] = $path;
            $elements = explode('.', $path);
            while (array_pop($elements) !== null) {
                $value = implode('.', $elements);
                $extended[$value] = $value;
            }
        }

        $regex = implode('[^.]+', array_map(
            function ($value) {
                return preg_quote($value, '~');
            },
            explode('*', $key)
        ));
        $regex = "~^{$regex}$~";

        return array_filter($extended, function ($value) use ($regex, $inverse) {
            if ($inverse) {
                return !preg_match($regex, $value);
            }
            return preg_match($regex, $value);
        });
    }

    private static function flatten(array $items, $prepend = ''): array
    {
        $flatten = [];
        foreach ($items as $key => $value) {
            if (is_array($value) && !empty($value)) {
                $flatten = array_merge(
                    $flatten,
                    self::flatten($value, $prepend . $key . '.')
                );
            } else {
                $flatten[$prepend . $key] = $value;
            }
        }

        return $flatten;
    }

}