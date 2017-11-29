<?php declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

/**
 * Trait DbHelper
 * @package App\Helpers
 */
trait DbHelper
{
    /**
     * Database transaction wrapper
     * @param callable $instructions
     * @return mixed
     * @throws \Exception
     */
    protected function wrapTransaction(callable $instructions) {
        DB::beginTransaction();
        try {
            $result = $instructions();
        } catch (\PDOException $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        DB::commit();

        return $result;
    }
}