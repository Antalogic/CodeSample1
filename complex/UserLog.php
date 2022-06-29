<?php

namespace App\Models;

use App\Services\{Assistant, GeoIP};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\Util\Json;

class UserLog extends Model
{
    protected $table = 'user_logs';

    public function country()
    {
        return $this->belongsTo(Country::class)->select('id', 'name', 'code');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param int $action
     * @param array|object|null $dataOriginal
     * @param array|object|null $dataChanged
     * @return bool
     */
    public static function add(int $action, array|object $dataOriginal = null, array|object $dataChanged = null): bool
    {
        $log = new UserLog();

        // Main variables
        $log->action = $action;
        $log->user_id = auth()->user()->id;
        $log->ip_address = Assistant::getIP();

        $dataOriginal = $log->convertObjectToArray($dataOriginal);
        $dataChanged = $log->convertObjectToArray($dataChanged);

        $log->getDiffBetweenData($dataOriginal, $dataChanged);

        if (!empty($dataOriginal)) {
            $log->data_original = Json::encode($dataOriginal);
        }

        if (!empty($dataChanged)) {
            $log->data_changed = Json::encode($dataChanged);
        }

        // Detecting country
        $country = GeoIP::getCountry($log->ip_address);
        if (!empty($country->id)) {
            $log->country_id = $country->id;
        }

        try {
            return $log->save();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param array|null|object $data
     */
    private function convertObjectToArray($data): array
    {
        return is_object($data) ? $data->attributesToArray() : (array)$data;
    }

    /**
     * @return false|null
     */
    private function getDiffBetweenData(array &$dataOriginal, array &$dataChanged)
    {
        if (array_keys($dataOriginal) != array_keys($dataChanged)) return false;

        $keys_keep = ['id', 'name', 'code', 'host'];
        $keys_skip = ['created_at', 'updated_at'];

        foreach ($dataOriginal as $key => $value) {
            if (in_array($key, $keys_keep)) {
                continue;
            }

            if (in_array($key, $keys_skip) || $dataOriginal[$key] == $dataChanged[$key]) {
                unset($dataChanged[$key]);
                unset($dataOriginal[$key]);
            }
        }
    }
}
