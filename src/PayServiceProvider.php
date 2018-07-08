<?php

namespace sunshangwu\LaravelPay;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use sunshangwu\Pay\Pay;

class PayServiceProvider extends ServiceProvider
{
    /**
     * Determin is defer.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Boot the service.
     *
     * @author sunshangwu <six@sunshangwu.cn>
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                dirname(__DIR__).'/config/pay.php' => config_path('pay.php'), ],
                'laravel-pay'
            );
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('pay');
        }
    }

    /**
     * Regist the service.
     *
     * @author sunshangwu <six@sunshangwu.cn>
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/config/pay.php', 'pay');

        $this->app->singleton('pay.alipay', function () {
            return Pay::alipay(config('pay.alipay'));
        });
        $this->app->singleton('pay.wechat', function () {
            return Pay::wechat(config('pay.wechat'));
        });
    }

    /**
     * Get services.
     *
     * @author sunshangwu <six@sunshangwu.cn>
     *
     * @return array
     */
    public function provides()
    {
        return ['pay.alipay', 'pay.wechat'];
    }
}
