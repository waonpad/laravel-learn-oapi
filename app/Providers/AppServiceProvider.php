<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** @var Application $app */
        $app = $this->app;

        /*
         * N+1を検出する
         *
         * @see [Laravelプロジェクトの初期設定 #PHP - Qiita](https://qiita.com/ucan-lab/items/8eab84e37421f907dea0#n1%E6%A4%9C%E5%87%BA%E8%A8%AD%E5%AE%9A)
         */
        Model::shouldBeStrict(!$app->isProduction());

        // dataプロパティでデータをラップしないようにする
        JsonResource::withoutWrapping();
    }
}
