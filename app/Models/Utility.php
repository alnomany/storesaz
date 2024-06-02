<?php

namespace App\Models;

use App\Mail\CommonEmailTemplate;
use App\Models\EmailTemplateLang;
use Illuminate\Database\Eloquent\Model;
use App\Models\Languages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Jenssegers\Date\Date;
use Twilio\Rest\Client;

class Utility extends Model
{
    private static $settings = null;
    private static $fetchsettings = null;
    private static $storageSetting = null;
    private static $colorset = null;
    private static $tableExist = null;
    private static $store = null;
    private static $themeSetting = null;
    private static $getStoreThemeSetting = null;
    private static $languages = null;

    public function createSlug($table, $title, $id = 0)
    {
        // Normalize the title
        $slug = Str::slug($title, '-');
        // Get any that could possibly be related.
        // This cuts the queries down by doing it once.
        $allSlugs = $this->getRelatedSlugs($table, $slug, $id);
        // If we haven't used it before then we are all good.
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }
        // Just append numbers like a savage until we find not used.
        for ($i = 1; $i <= 100; $i++) {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                return $newSlug;

            }
        }
        throw new \Exception('Can not create a unique slug');
    }

    protected function getRelatedSlugs($table, $slug, $id = 0)
    {
        return DB::table($table)->select()->where('slug', 'like', $slug . '%')->where('id', '<>', $id)->get();
    }

    public static function settings()
    {
        if (self::$fetchsettings === null) {
            $data = DB::table('settings');

            if (\Auth::check()) {
                $data = $data->where('created_by', '=', \Auth::user()->creatorId());
                if (\Auth::user()->type != 'super admin') {
                    $data = $data->where('store_id', \Auth::user()->current_store);
                } else {
                    $data = $data->where('store_id', '0');
                }
                if ($data->count() === 0) {
                    $data = DB::table('settings')->where('created_by', 1);
                }

            } else {
                $data = $data->where('created_by', '=', 1);
            }
            $data = $data->get();
            self::$fetchsettings = $data;
        }
       
        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "currency_symbol_position" => "pre",
            "logo_dark" => "logo-dark.png",
            "logo_light" => "logo-light.png",
            "currency_symbol" => "",
            "currency" => "",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "invoice_prefix" => "#INV",
            "invoice_color" => "ffffff",
            "quote_template" => "template1",
            "quote_color" => "ffffff",
            "salesorder_template" => "template1",
            "salesorder_color" => "ffffff",
            "proposal_prefix" => "#PROP",
            "proposal_color" => "fffff",
            "bill_prefix" => "#BILL",
            "bill_color" => "fffff",
            "quote_prefix" => "#QUO",
            "salesorder_prefix" => "#SOP",
            "vender_prefix" => "#VEND",
            "footer_title" => "",
            "footer_notes" => "",
            "invoice_template" => "template1",
            "bill_template" => "template1",
            "proposal_template" => "template1",
            "default_language" => "en",
            "decimal_number" => "2",
            "tax_type" => "VAT",
            "shipping_display" => "on",
            "footer_link_1" => "Support",
            "footer_value_1" => "#",
            "footer_link_2" => "Terms",
            "footer_value_2" => "#",
            "footer_link_3" => "Privacy",
            "footer_value_3" => "#",
            "display_landing_page" => "on",
            "title_text" => "",
            "footer_text" => "",
            "company_logo_light" => "logo-light.png",
            "company_logo_dark" => "logo-dark.png",
            "company_favicon" => "",
            "gdpr_cookie" => "",
            "cookie_text" => "",
            "signup_button" => "on",
            "email_verification" => "on",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "color" => "theme-3",
            "SITE_RTL" => "off",
            "is_checkout_login_required" => "on",
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url" => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
            'enable_cookie'=>'on',
            'necessary_cookies'=>'on',
            'cookie_logging'=>'on',
            'cookie_title'=>'We use cookies!',
            'cookie_description'=>'Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it.',
            'strictly_cookie_title'=>'Strictly necessary cookies',
            'strictly_cookie_description'=>'These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly',
            'more_information_description'=>'For any queries in relation to our policy on cookies and your choices, please contact us',
            'contactus_url'=>'#',
            'chatgpt_key'=>'',
            'disable_lang'=>'',
            'mail_driver'=>'',
            'mail_host'=>'',
            'mail_port'=>'',
            'mail_username'=>'',
            'mail_password'=>'',
            'mail_encryption'=>'',
            'mail_from_name'=>'',
            'mail_from_address'=>'',
            "timezone"=>"Asia/Kolkata",
            "color_flag"=>"false",
            "chatgpt_model_name"=>"",
        ];

        foreach (self::$fetchsettings as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    
    }
    public static function checktableExist(){
        if(is_null(self::$tableExist)){
            $table = \Schema::hasTable('languages');
            self::$tableExist = $table;
        }
        return self::$tableExist;
    }

    public static function languages()
    {
        if(is_null(self::$languages)){
            $languages=Utility::langList();
            if(Utility::checktableExist()){
                $settings = Utility::langSetting();
                if(!empty($settings['disable_lang'])){
                    $disabledlang = explode(',', $settings['disable_lang']);
                    $languages = Languages::whereNotIn('code',$disabledlang)->pluck('fullName','code');
                }
                else{
                    $languages = Languages::pluck('fullName','code');
                }
            }
            self::$languages = $languages;
        }

        return self::$languages;
    }

    public static function getValByName($key)
    {
        $setting = Utility::settings();

        if (!isset($setting[$key]) || empty($setting[$key])) {
            $setting[$key] = '';
        }
        return $setting[$key];
    }

    public static function getPaymentSetting($store_id = null)
    {
        $data = DB::table('store_payment_settings');
        $settings = [];
        if (\Auth::check()) {
            $store_id = \Auth::user()->current_store;
            $data = $data->where('store_id', '=', $store_id);

        } else {
            $data = $data->where('store_id', '=', $store_id);
        }
        $data = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }
    public static function getThemeData($store_id , $theme_name){
        if(is_null(self::$getStoreThemeSetting)){
            $data = DB::table('store_theme_settings');
    
            if ($store_id == null) {
                if (\Auth::check()) {
                    $store_id = \Auth::user()->current_store;
                }else {
                    $store_id = 0;
                }
            }
            if (\Auth::check()) {
                $data = $data->where('store_id', '=', $store_id)->where('theme_name', $theme_name);
            } else {
                $data = $data->where('store_id', '=', $store_id)->where('theme_name', $theme_name);
            }
            $data = $data->get();
            self::$getStoreThemeSetting = $data;
        }   
        return self::$getStoreThemeSetting;
    }
    public static function getStoreThemeSetting($store_id = null, $theme_name = null)
    {
        $settings = [];
        $data =  Utility::getThemeData($store_id,$theme_name);
        if ($data->count() > 0) {
            foreach ($data as $row) {
                $settings[$row->name] = $row->value;
            }
        }
        return $settings;
           
    }

    public static function getDateFormated($date, $time = false)
    {
        if (!empty($date) && $date != '0000-00-00') {
            if ($time == true) {
                return date("d M Y H:i A", strtotime($date));
            } else {
                return date("d M Y", strtotime($date));
            }
        } else {
            return '';
        }
    }

    public static function demoStoreThemeSetting($store_id = null, $theme_name = null)
    {
        if(is_null(self::$themeSetting)){
            self::$themeSetting = Utility::getThemeData($store_id,$theme_name);
        }
        $data = self::$themeSetting;
        $settings = [
            "enable_top_bar" => "on",
            "top_bar_title" => "FREE SHIPPING world wide for all orders over $199",
            "top_bar_number" => "(212) 308-1220",
            "top_bar_whatsapp" => "https://web.whatsapp.com/",
            "top_bar_instagram" => "https://instagram.com/",
            "top_bar_twitter" => "https://twitter.com/",
            "top_bar_messenger" => "https://messenger.com/",

            "enable_header_img" => "on",
            "header_title" => "Home Accessories",
            "header_desc" => "There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.",
            "button_text" => "Start shopping",
            "header_img" => "header_img_1.png",

            "enable_features" => "on",
            "enable_features1" => "on",
            "enable_features2" => "on",
            "enable_features3" => "on",

            "features_icon1" => '<i class="fa fa-tags"></i>',
            "features_title1" => 'Many promotions',
            "features_description1" => 'From pixel-perfect icons and scalable vector graphics, to full user flows',

            "features_icon2" => '<i class="fas fa-store"></i>',
            "features_title2" => 'Many promotions',
            "features_description2" => 'From pixel-perfect icons and scalable vector graphics, to full user flows',

            "features_icon3" => '<i class="fa fa-percentage"></i>',
            "features_title3" => 'Many promotions',
            "features_description3" => 'From pixel-perfect icons and scalable vector graphics, to full user flows',

            "enable_email_subscriber" => "on",
            "subscriber_title" => "Always on time",
            "subscriber_sub_title" => "There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.",
            "subscriber_img" => "email_subscriber_1.png",

            "enable_categories" => "on",
            "categories" => "Categories",
            "categories_title" => "There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.",

            "enable_testimonial" => "on",

            "enable_testimonial1" => "on",
            "testimonial_main_heading_title" => 'There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.',
            "testimonial_main_heading" => 'Testimonial',
            "testimonial_img1" => 'avatar.png',

            "testimonial_name1" => 'Rajodiya Infotech',
            "testimonial_about_us1" => 'CEO Rajodiya Infotech',
            "testimonial_description1" => '‘Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital lending strategy to make most popular streamline the web process',

            "enable_testimonial2" => "on",
            "testimonial_img2" => 'avatar.png',
            "testimonial_name2" => 'Rajodiya Infotech',
            "testimonial_about_us2" => 'CEO Rajodiya Infotech',
            "testimonial_description2" => '‘Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital lending strategy to make most popular streamline the web process',

            "enable_testimonial3" => "on",
            "testimonial_img3" => 'avatar.png',
            "testimonial_name3" => 'Rajodiya Infotech',
            "testimonial_about_us3" => 'CEO Rajodiya Infotech',
            "testimonial_description3" => '‘Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital lending strategy to make most popular streamline the web process',

            "enable_brand_logo" => "on",
            "brand_logo" => implode(
                ',', [
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                    'brand_logo.png',
                ]
            ),

            "quick_link_header_name21" => "About",
            "quick_link_header_name41" => "Company",

            "quick_link_name1" => __('Home Pages'),
            "quick_link_url1" => '#Home Pages',

            "enable_footer_note" => "on",
            "enable_quick_link1" => "on",
            "enable_quick_link2" => "on",
            "enable_quick_link3" => "on",
            "enable_quick_link4" => "on",

            "quick_link_header_name1" => __("Theme Pages"),
            "quick_link_header_name2" => __("About"),
            "quick_link_header_name3" => __("Company"),
            "quick_link_header_name4" => __("Company"),

            "quick_link_name11" => __('Home Pages'),
            "quick_link_name12" => __('Pricing'),
            "quick_link_name13" => __('Contact Us'),
            "quick_link_name14" => __('Team'),

            "quick_link_name21" => __('Blog'),
            "quick_link_name22" => __('Help Center'),
            "quick_link_name23" => __('Sales Tools Catalog'),
            "quick_link_name24" => __('Academy'),

            "quick_link_name31" => __('Terms and Policy'),
            "quick_link_name32" => __('About us'),
            "quick_link_name33" => __('Support'),
            "quick_link_name34" => __('About us'),

            "quick_link_name41" => __('Terms and Policy'),
            "quick_link_name42" => __('About us'),
            "quick_link_name43" => __('Support'),
            "quick_link_name44" => __('About us'),

            "quick_link_url11" => '#Home Pages',
            "quick_link_url12" => '#Home Pages',
            "quick_link_url13" => '#Home Pages',
            "quick_link_url14" => '#Home Pages',

            "quick_link_url21" => '#Blog',
            "quick_link_url22" => '#Blog',
            "quick_link_url23" => '#Blog',
            "quick_link_url24" => '#Blog',

            "quick_link_url31" => '#Terms and Policy',
            "quick_link_url32" => '#Terms and Policy',
            "quick_link_url33" => '#Terms and Policy',
            "quick_link_url34" => '#Terms and Policy',

            "quick_link_url41" => '#About us',
            "quick_link_url42" => '#About us',
            "quick_link_url43" => '#About us',
            "quick_link_url44" => '#About us',

            "footer_logo" => "footer_logo.png",
            "footer_desc" => "Nowadays, it isnt great uncommon to see lenders rapidly adopting a new digital",
            "footer_number" => "(987)654321",

            "enable_footer" => "on",
            "email" => "test@test.com",
            "whatsapp" => "https://api.whatsapp.com/",
            "facebook" => "https://www.facebook.com/",
            "instagram" => "https://www.instagram.com/",
            "twitter" => "https://twitter.com/",
            "youtube" => "https://www.youtube.com/",
            "footer_note" => "© 2021 My Store. All rights reserved",
            "storejs" => "<script>console.log('hello');</script>",

            /*THEME 3*/

        ];

        if ($theme_name == 'theme2') {
            $settings['header_img'] = 'header_img_2.png';
            $settings['subscriber_img'] = "email_subscriber_2.png";
            $settings['footer_logo2'] = "footer_logo2.png";
            $settings['brand_logo'] = implode(
                ',', [
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                    'brand_logo2.png',
                ]
            );
        }

        if ($theme_name == 'theme3') {
            $settings['header_img'] = 'header_img_3.png';
            $settings['testimonial_img1'] = 'testimonail-img_3.png';
            $settings['testimonial_img2'] = 'testimonail-img_3.png';
            $settings['testimonial_img3'] = 'testimonail-img_3.png';
            $settings['banner_img'] = 'header_img_3.png';
            $settings['enable_banner_img'] = 'on';
            $settings['testimonial_main_heading_title'] = 'StoreGo';
            $settings['footer_logo3'] = "footer_logo3.png";

        }

        if ($theme_name == 'theme4') {
            $settings['header_img'] = 'header_img_4.png';
            $settings['banner_img'] = 'image-big-4.jpg';
            $settings['enable_banner_img'] = 'on';
            $settings['subscriber_img'] = "email_subscriber_2.png";
            $settings['brand_logo'] = implode(
                ',', [
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                    'brand_logo4.png',
                ]
            );
            $settings['footer_logo4'] = "footer_logo4.png";
        }

        if ($theme_name == 'theme5') {
            $settings['header_img'] = 'header_img_5.png';
            $settings['brand_logo'] = implode(
                ',', [
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                    'brand_logo5.png',
                ]
            );
            $settings['footer_logo5'] = "footer_logo5.png";
        }

        if ($data->count() > 0) {
            foreach ($data as $row) {
                $settings[$row->name] = $row->value;
            }
        }

        return $settings;
    }

    public static function getAdminPaymentSetting()
    {
        $data = DB::table('admin_payment_settings');
        $settings = [];
        if (\Auth::check()) {
            $user_id = 1;
            $data = $data->where('created_by', '=', $user_id);

        }
        $data = $data->get();
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}='{$envValue}'\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";
        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }

    public static function templateData()
    {
        $arr = [];
        $arr['colors'] = [
            '003580',
            '666666',
            '6676ef',
            'f50102',
            'f9b034',
            'fbdd03',
            'c1d82f',
            '37a4e4',
            '8a7966',
            '6a737b',
            '050f2c',
            '0e3666',
            '3baeff',
            '3368e6',
            'b84592',
            'f64f81',
            'f66c5f',
            'fac168',
            '46de98',
            '40c7d0',
            'be0028',
            '2f9f45',
            '371676',
            '52325d',
            '511378',
            '0f3866',
            '48c0b6',
            '297cc0',
            'ffffff',
            '000',
        ];
        $arr['templates'] = [
            "template1" => "New York",
            "template2" => "Toronto",
            "template3" => "Rio",
            "template4" => "London",
            "template5" => "Istanbul",
            "template6" => "Mumbai",
            "template7" => "Hong Kong",
            "template8" => "Tokyo",
            "template9" => "Sydney",
            "template10" => "Paris",
        ];

        return $arr;
    }

    public static function themeOne()
    {
        $arr = [];

        $arr = [
            'theme1' => [
                'theme1-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme1/Home.png')),
                    'color' => '92bd88',
                ],
                'theme1-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme1/Home-1.png')),
                    'color' => '276968',
                ],
                'theme1-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme1/Home-2.png')),
                    'color' => 'af8637',
                ],
                'theme1-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme1/Home-3.png')),
                    'color' => 'e7d7bd',
                ],
                'theme1-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme1/Home-4.png')),
                    'color' => 'b7786f',
                ],
            ],

            'theme2' => [
                'theme2-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home.png')),
                    'color' => 'f5ba20',
                ],
                'theme2-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-1.png')),
                    'color' => 'fa747d',
                ],
                'theme2-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-2.png')),
                    'color' => 'c8ae9d',
                ],
                'theme2-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-3.png')),
                    'color' => 'd7e2dc',
                ],
                'theme2-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme2/Home-4.png')),
                    'color' => '5ea5ab',
                ],
            ],

            'theme3' => [
                'theme3-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home.png')),
                    'color' => 'f6e32f',
                ],
                'theme3-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-1.png')),
                    'color' => '7db802',
                ],
                'theme3-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-2.png')),
                    'color' => '3e77ea',
                ],
                'theme3-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-3.png')),
                    'color' => '2b2d2d',
                ],
                'theme3-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme3/Home-4.png')),
                    'color' => 'ffccb4',
                ],
            ],

            'theme4' => [
                'theme4-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home.png')),
                    'color' => '5e7698',
                ],
                'theme4-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-1.png')),
                    'color' => '88d297',
                ],
                'theme4-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-2.png')),
                    'color' => 'c9aea7',
                ],
                'theme4-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-3.png')),
                    'color' => '2f343a',
                ],
                'theme4-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme4/Home-4.png')),
                    'color' => 'f3ba51',
                ],
            ],

            'theme5' => [
                'theme5-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home.png')),
                    'color' => '007aff',
                ],
                'theme5-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-1.png')),
                    'color' => 'febd00',
                ],
                'theme5-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-2.png')),
                    'color' => '05d79f',
                ],
                'theme5-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-3.png')),
                    'color' => 'e91e63',
                ],
                'theme5-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme5/Home-4.png')),
                    'color' => '2b2d42',
                ],
            ],

            'theme6' => [
                'theme6-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home.png')),
                    'color' => '94ce79',
                ],
                'theme6-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-1.png')),
                    'color' => '79ceb4',
                ],
                'theme6-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-2.png')),
                    'color' => 'f4b41a',
                ],
                'theme6-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-3.png')),
                    'color' => '1877f2',
                ],
                'theme6-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme6/Home-4.png')),
                    'color' => 'e6007e',

                ],
            ],

            'theme7' => [
                'theme7-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home.png')),
                    'color' => '2b2d42',
                ],
                'theme7-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-1.png')),
                    'color' => '54a089',
                ],
                'theme7-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-2.png')),
                    'color' => '615144',
                ],
                'theme7-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-3.png')),
                    'color' => '1877f2',
                ],
                'theme7-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme7/Home-4.png')),
                    'color' => '92be35',

                ],
            ],

            'theme8' => [
                'theme8-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home.png')),
                    'color' => '3E3E37',
                ],
                'theme8-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-1.png')),
                    'color' => '615144',
                ],
                'theme8-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-2.png')),
                    'color' => '54a085',
                ],
                'theme8-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-3.png')),
                    'color' => '6e00ff',
                ],
                'theme8-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme8/Home-4.png')),
                    'color' => '7e7d7c',

                ],
            ],

            'theme9' => [
                'theme9-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home.png')),
                    'color' => '000000',
                ],
                'theme9-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-1.png')),
                    'color' => '793838',
                ],
                'theme9-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-2.png')),
                    'color' => '4a7a6c',
                ],
                'theme9-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-3.png')),
                    'color' => '0f3e7a',
                ],
                'theme9-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme9/Home-4.png')),
                    'color' => '848484',

                ],
            ],

            'theme10' => [
                'theme10-v1' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home.png')),
                    'color' => '256dff',
                ],
                'theme10-v2' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-1.png')),
                    'color' => 'e6007e',
                ],
                'theme10-v3' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-2.png')),
                    'color' => 'f25c05',
                ],
                'theme10-v4' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-3.png')),
                    'color' => '210070',
                ],
                'theme10-v5' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-4.png')),
                    'color' => 'f4b41a',

                ],
                'theme10-v6' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-5.png')),
                    'color' => '1f3767',

                ],
                'theme10-v7' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-6.png')),
                    'color' => '727272',

                ],
                'theme10-v8' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-7.png')),
                    'color' => 'ff1f00',

                ],
                'theme10-v9' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-8.png')),
                    'color' => '004870',

                ],
                'theme10-v10' => [
                    'img_path' => asset(Storage::url('uploads/store_theme/theme10/Home-9.png')),
                    'color' => '425b23',

                ],
            ],
        ];

        return $arr;
    }
    public static function priceFormat($price, $slug = null)
    {
        $settings = Utility::settings();
        if (\Auth::check() && \Auth::User()->type == 'Owner') {
            if(is_null(self::$store)){
                $store = Store::find(Auth::user()->current_store);
                self::$store = $store;
            }
            $settings = self::$store;
            if ($settings['currency_symbol_position'] == "pre" && $settings['currency_symbol_space'] == "with") {
                return $settings['currency'] . ' ' . number_format($price, isset($settings->decimal_number) ? $settings->decimal_number : 2);
            } elseif ($settings['currency_symbol_position'] == "pre" && $settings['currency_symbol_space'] == "without") {
                return $settings['currency'] . number_format($price, isset($settings->decimal_number) ? $settings->decimal_number : 2);
            } elseif ($settings['currency_symbol_position'] == "post" && $settings['currency_symbol_space'] == "with") {
                return number_format($price, isset($settings->decimal_number) ? $settings->decimal_number : 2) . ' ' . $settings['currency'];
            } elseif ($settings['currency_symbol_position'] == "post" && $settings['currency_symbol_space'] == "without") {
                return number_format($price, isset($settings->decimal_number) ? $settings->decimal_number : 2) . $settings['currency'];
            }

        } else {
            if (!isset($slug)) {
                $slug = session()->get('slug');
            }

            if (!empty($slug)) {
                if(is_null(self::$store)){
                    $store = Store::where('slug', $slug)->first();
                    self::$store = $store;
                }
                $store = self::$store;

                if ($store['currency_symbol_position'] == "pre" && $store['currency_symbol_space'] == "with") {
                    return $store['currency'] . ' ' . number_format($price, isset($store->decimal_number) ? $store->decimal_number : 2);
                } elseif ($store['currency_symbol_position'] == "pre" && $store['currency_symbol_space'] == "without") {
                    return $store['currency'] . number_format($price, isset($store->decimal_number) ? $store->decimal_number : 2);
                } elseif ($store['currency_symbol_position'] == "post" && $store['currency_symbol_space'] == "with") {
                    return number_format($price, isset($store->decimal_number) ? $store->decimal_number : 2) . ' ' . $store['currency'];
                } elseif ($store['currency_symbol_position'] == "post" && $store['currency_symbol_space'] == "without") {
                    return number_format($price, isset($store->decimal_number) ? $store->decimal_number : 2) . $store['currency'];
                }
            }

            //            return (($settings['currency_symbol_position'] == "pre") ? $settings['currency_symbol'] : '') . number_format($price, 2) . (($settings['currency_symbol_position'] == "post") ? $settings['currency_symbol'] : '');
            return (($settings['currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, Utility::getValByName('decimal_number')) . (($settings['currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
        }
    }

    public static function currencySymbol($settings)
    {
        return $settings['site_currency_symbol'];
    }

    public static function timeFormat($settings, $time)
    {
        return date($settings['site_date_format'], strtotime($time));
    }

    public static function dateFormat($date)
    {
        $settings = Utility::settings();

        return date($settings['site_date_format'], strtotime($date));
    }

    public static function proposalNumberFormat($settings, $number)
    {
        return $settings["proposal_prefix"] . sprintf("%05d", $number);
    }

    public static function billNumberFormat($settings, $number)
    {
        return $settings["bill_prefix"] . sprintf("%05d", $number);
    }

    public static function tax($taxes)
    {
        $taxArr = explode(',', $taxes);
        $taxes = [];
        foreach ($taxArr as $tax) {
            $taxes[] = ProductTax::find($tax);
        }

        return $taxes;
    }

    public static function taxRate($taxRate, $price, $quantity)
    {

        return ($taxRate / 100) * ($price * $quantity);
    }

    public static function totalTaxRate($taxes)
    {

        $taxArr = explode(',', $taxes);
        $taxRate = 0;

        foreach ($taxArr as $tax) {

            $tax = ProductTax::find($tax);
            $taxRate += !empty($tax->rate) ? $tax->rate : 0;
        }

        return $taxRate;
    }

    // get font-color code accourding to bg-color
    public static function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = array(
            $r,
            $g,
            $b,
        );

        //return implode(",", $rgb); // returns the rgb values separated by commas
        return $rgb; // returns an array with the rgb values
    }

    public static function getFontColor($color_code)
    {
        $rgb = self::hex2rgb($color_code);
        $R = $G = $B = $C = $L = $color = '';

        $R = (floor($rgb[0]));
        $G = (floor($rgb[1]));
        $B = (floor($rgb[2]));

        $C = [
            $R / 255,
            $G / 255,
            $B / 255,
        ];

        for ($i = 0; $i < count($C); ++$i) {
            if ($C[$i] <= 0.03928) {
                $C[$i] = $C[$i] / 12.92;
            } else {
                $C[$i] = pow(($C[$i] + 0.055) / 1.055, 2.4);
            }
        }

        $L = 0.2126 * $C[0] + 0.7152 * $C[1] + 0.0722 * $C[2];

        if ($L > 0.179) {
            $color = 'black';
        } else {
            $color = 'white';
        }

        return $color;
    }

    public static function delete_directory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    public static function getSuperAdminValByName($key)
    {
        $data = DB::table('settings');
        $data = $data->where('name', '=', $key);
        $data = $data->first();
        if (!empty($data)) {
            $record = $data->value;
        } else {
            $record = '';
        }

        return $record;
    }

    // used for replace email variable (parameter 'template_name','id(get particular record by id for data)')
    public static function replaceVariable($content, $obj)
    {
        $arrVariable = [
            '{store_name}',
            '{order_no}',
            '{customer_name}',
            '{billing_address}',
            '{billing_country}',
            '{billing_city}',
            '{billing_postalcode}',
            '{shipping_address}',
            '{shipping_country}',
            '{shipping_city}',
            '{shipping_postalcode}',
            '{item_variable}',
            '{qty_total}',
            '{sub_total}',
            '{discount_amount}',
            '{shipping_amount}',
            '{total_tax}',
            '{final_total}',
            '{sku}',
            '{quantity}',
            '{product_name}',
            '{variant_name}',
            '{item_tax}',
            '{item_total}',
        ];
        $arrValue = [
            'store_name' => '',
            'order_no' => '',
            'customer_name' => '',
            'billing_address' => '',
            'billing_country' => '',
            'billing_city' => '',
            'billing_postalcode' => '',
            'shipping_address' => '',
            'shipping_country' => '',
            'shipping_city' => '',
            'shipping_postalcode' => '',
            'item_variable' => '',
            'qty_total' => '',
            'sub_total' => '',
            'discount_amount' => '',
            'shipping_amount' => '',
            'total_tax' => '',
            'final_total' => '',
            'sku' => '',
            'quantity' => '',
            'product_name' => '',
            'variant_name' => '',
            'item_tax' => '',
            'item_total' => '',
        ];

        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }

        $arrValue['app_name'] = env('APP_NAME');
        $arrValue['app_url'] = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';

        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    // Email Template Modules Function START

    public static function userDefaultData()
    {
        // Make Entry In User_Email_Template
        $allEmail = EmailTemplate::all();
        foreach ($allEmail as $email) {
            UserEmailTemplate::create(
                [
                    'template_id' => $email->id,
                    'user_id' => 1,
                    'is_active' => 1,
                ]
            );
        }
    }

    // Common Function That used to send mail with check all cases
    public static function sendEmailTemplate($emailTemplate, $mailTo, $obj, $store, $order=null)
    {
        // find template is exist or not in our record
        $template = EmailTemplate::where('name', 'LIKE', $emailTemplate)->first();

        if (isset($template) && !empty($template)) {
            // check template is active or not by company
            $is_actives = UserEmailTemplate::where('template_id', '=', $template->id)->first();

            if ($is_actives->is_active == 1) {
                // get email content language base
                $content = EmailTemplateLang::where('parent_id', '=', $template->id)->where('lang', 'LIKE', $store->lang)->first();

                $content->from = $template->from;

                if (!empty($content->content)) {
                    $content->content = self::replaceVariables($content->content, $obj, $store, $order);//$order=null

                    // send email
                    try
                    {

                        $user = Auth::user();
                        if($user == null || Auth::user()->type == 'super admin'){
                            $settings = Utility::settingsById(1);
                            config(
                                [
                                    'mail.driver' => isset($settings['mail_driver']) ? $settings['mail_driver'] : '',
                                    'mail.host' => isset($settings['mail_host']) ? $settings['mail_host'] : '',
                                    'mail.port' => isset($settings['mail_port']) ? $settings['mail_port'] : '',
                                    'mail.encryption' => isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '',
                                    'mail.username' => isset($settings['mail_username']) ? $settings['mail_username'] : '',
                                    'mail.password' => isset($settings['mail_password']) ? $settings['mail_password'] : '',
                                    'mail.from.address' => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                                    'mail.from.name' => isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '',
                                ]
                            );
                        }else{
                            if (isset($store->mail_driver) && !empty($store->mail_driver))
                            {
                                config(
                                    [
                                        'mail.driver' => $store->mail_driver,
                                        'mail.host' => $store->mail_host,
                                        'mail.port' => $store->mail_port,
                                        'mail.encryption' => $store->mail_encryption,
                                        'mail.username' => $store->mail_username,
                                        'mail.password' => $store->mail_password,
                                        'mail.from.address' => $store->mail_from_address,
                                        'mail.from.name' => $store->mail_from_name,
                                    ]
                                );
                            }else{
                                $settings = Utility::settingsById(1);
                                config(
                                    [
                                        'mail.driver' => isset($settings['mail_driver']) ? $settings['mail_driver'] : '',
                                        'mail.host' => isset($settings['mail_host']) ? $settings['mail_host'] : '',
                                        'mail.port' => isset($settings['mail_port']) ? $settings['mail_port'] : '',
                                        'mail.encryption' => isset($settings['mail_encryption']) ? $settings['mail_encryption'] : '',
                                        'mail.username' => isset($settings['mail_username']) ? $settings['mail_username'] : '',
                                        'mail.password' => isset($settings['mail_password']) ? $settings['mail_password'] : '',
                                        'mail.from.address' => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                                        'mail.from.name' => isset($settings['mail_from_name']) ? $settings['mail_from_name'] : '',
                                    ]
                                );
                            }
                        }
                        if($order != null){
                            $orders = Order::find(Crypt::decrypt($order));
                        }
                        $ownername = User::where('id', $store->created_by)->first();
                        if ($mailTo == $ownername->email) {

                            Mail::to(
                                [
                                    $store['email'],
                                ]
                            )->send(new CommonEmailTemplate($content, $store));

                        } else {

                            Mail::to(
                                [
                                    $mailTo,

                                ]
                            )->send(new CommonEmailTemplate($content, $store));

                            $user = (Auth::guard('customers')->user());
                        }

                    } catch (\Exception $e) {
                        $error = __('E-Mail has been not sent due to SMTP configuration');
                    }
                    if (isset($error)) {
                        $arReturn = [
                            'is_success' => false,
                            'error' => $error,
                        ];
                    } else {
                        $arReturn = [
                            'is_success' => true,
                            'error' => false,
                        ];
                    }
                } else {
                    $arReturn = [
                        'is_success' => false,
                        'error' => __('Mail not send, email is empty'),
                    ];
                }
                return $arReturn;
            } else {
                return [
                    'is_success' => true,
                    'error' => false,
                ];
            }
        } else {
            return [
                'is_success' => false,
                'error' => __('Mail not send, email not found'),
            ];
        }
        //        }
    }

    // used for replace email variable (parameter 'template_name','id(get particular record by id for data)')
    public static function replaceVariables($content, $obj, $store, $order=null)
    {
        $arrVariable = [
            '{app_name}',
            '{order_name}',
            '{order_status}',
            '{app_url}',
            '{store_url}',
            '{order_url}',
            '{order_id}',
            '{owner_name}',
            '{owner_email}',
            '{owner_password}',
            '{order_date}',
        ];
        $arrValue = [
            'app_name' => '-',
            'order_name' => '-',
            'order_status' => '-',
            'app_url' => '-',
            'store_url' => '-',
            'order_url' => '-',
            'order_id' => '-',
            'owner_name' => '-',
            'owner_email' => '-',
            'owner_password' => '-',
            'order_date' => '-',
        ];
        foreach ($obj as $key => $val) {
            $arrValue[$key] = $val;
        }

        $arrValue['app_name'] = $store->name;
        $arrValue['app_url'] = '<a href="' . env('APP_URL') . '" target="_blank">' . env('APP_URL') . '</a>';
        $arrValue['store_url'] = '<a href="' . env('APP_URL') . '/' . $store->slug. '" target="_blank">' . env('APP_URL') . '/' . $store->slug . '</a>';
        $arrValue['order_url'] = '<a href="' . env('APP_URL') . '/' . $store->slug . '/order/' . $order . '" target="_blank">' . env('APP_URL') . '/' . $store->slug . '/order/' . $order . '</a>';

        $ownername = User::where('id', $store->created_by)->first();
        if($order != null){
            $id = Crypt::decrypt($order);
            $order = Order::where('id', $id)->first();
        }
        $arrValue['owner_name'] = $ownername->name;

        if($order != null){
            $arrValue['order_id'] = isset($order->order_id) ? $order->order_id : 0;
            $arrValue['order_date'] = isset($order->order_id) ? self::dateFormat($order->created_at) : 0;
        }

        return str_replace($arrVariable, array_values($arrValue), $content);
    }

    // Make Entry in email_tempalte_lang table when create new language
    public static function makeEmailLang($lang)
    {
        $template = EmailTemplate::all();
        foreach ($template as $t) {
            $default_lang = EmailTemplateLang::where('parent_id', '=', $t->id)->where('lang', 'LIKE', 'en')->first();
            $emailTemplateLang = new EmailTemplateLang();
            $emailTemplateLang->parent_id = $t->id;
            $emailTemplateLang->lang = $lang;
            $emailTemplateLang->subject = $default_lang->subject;
            $emailTemplateLang->content = $default_lang->content;
            $emailTemplateLang->save();
        }
    }

    // For Email template Module
    public static function defaultEmail()
    {
        // Email Template
        $emailTemplate = [
            'Order Created',
            'Status Change',
            'Order Created For Owner',
            'Owner And Store Created',
        ];

        foreach ($emailTemplate as $eTemp) {
            EmailTemplate::create(
                [
                    'name' => $eTemp,
                    'from' => env('APP_NAME'),
                    'created_by' => 1,
                ]
            );
        }

        $defaultTemplate = [
            'Order Created' => [
                'subject' => 'Order Complete',
                'lang' => [
                    'ar' => '<p>مرحبا ،</p><p>مرحبا بك في {app_name}.</p><p>مرحبا ، {order_name} ، شكرا للتسوق</p><p>قمنا باستلام طلب الشراء الخاص بك ، سيتم الاتصال بك قريبا !</p><p>شكرا ،</p><p>{app_name}</p><p>{order_url}</p>',
                    'zh' => '<p>您好，</p><p>欢迎来到 {app_name}。</p><p>您好，{order_name}，感谢您的购物</p><p>我们已收到您的购买请求，我们很快就会与您联系！</p><p>谢谢，</p><p>{app_name}</p><p>{order_url}</p>',
                    'da' => '<p>Hej, &nbsp;</p><p>Velkommen til {app_name}.</p><p>Hej, {order_name}, tak for at Shopping</p><p>Vi har modtaget din købsanmodning.</p><p>Tak,</p><p>{app_navn}</p><p>{order_url}</p>',
                    'de' => '<p>Hello, &nbsp;</p><p>Willkommen bei {app_name}.</p><p>Hi, {order_name}, Vielen Dank für Shopping</p><p>Wir haben Ihre Kaufanforderung erhalten, wir werden in Kürze in Kontakt sein!</p><p>Danke,</p><p>{app_name}</p><p>{order_url}</p>',
                    'en' => '<p>Hello,&nbsp;</p><p>Welcome to {app_name}.</p><p>Hi, {order_name}, Thank you for Shopping</p><p>We received your purchase request, we\'ll be in touch shortly!</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                    'es' => '<p>Hola, &nbsp;</p><p>Bienvenido a {app_name}.</p><p>Hi, {order_name}, Thank you for Shopping</p><p>Recibimos su solicitud de compra, ¡estaremos en contacto en breve!</p><p>Gracias,</p><p>{app_name}</p><p>{order_url}</p>',
                    'fr' => '<p>Bonjour, &nbsp;</p><p>Bienvenue dans {app_name}.</p><p>Hi, {order_name}, Thank you for Shopping</p><p>We reçus your purchase request, we \'ll be in touch incess!</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                    'he' => '<p>שלום,&nbsp;</p><p>ברוך הבא ל{app_name}.</p><p>היי, {order_name}, תודה על הקניות</p><p>קיבלנו את בקשת הרכישה שלך, ניצור קשר בקרוב!</p><p>תודה,</p><p>{app_name}</p><p>{order_url}</p>',
                    'pt' => '<p>NAVE ÓRICA-Тутутутугальстугальский (app_name}).</p><p>Hi, {order_name}, пасссский</p><p>польстугальский потугальский (португальский), "скортугальский".</p><p>nome_do_appсссский!</p><p>{app_name}</p><p>{app_name}</p><p>{order_url}</p>',
                    'it' => '<p>Ciao, &nbsp;</p><p>Benvenuti in {app_name}.</p><p>Ciao, {order_name}, Grazie per Shopping</p><p>Abbiamo ricevuto la tua richiesta di acquisto, noi \ saremo in contatto a breve!</p><p>Grazie,</p><p>{app_name}</p><p>{order_url}</p>',
                    'ja' => '<p>こんにちは &nbsp;</p><p>{app_name}へようこそ。</p></p><p><p>こんにちは、 {order_name}、お客様の購買要求書をお受け取りいただき、すぐにご連絡いたします。</p><p>ありがとうございます。</p><p>{app_name}</p><p>{order_url}</p>',
                    'nl' => '<p>Hallo, &nbsp;</p><p>Welkom bij {app_name}.</p><p>Hallo, {order_name}, Dank u voor Winkelen</p><p>We hebben uw aankoopaanvraag ontvangen, we zijn binnenkort in contact!</p><p>Bedankt,</p><p>{ app_name }</p><p>{order_url}</p>',
                    'pl' => '<p>Hello, &nbsp;</p><p>Witamy w aplikacji {app_name}.</p><p>Hi, {order_name}, Dziękujemy za zakupy</p><p>Otrzymamy Twój wniosek o zakup, wkrótce będziemy w kontakcie!</p><p>Dzięki,</p><p>{app_name}</p><p>{order_url}</p>',
                    'ru' => '<p>Здравствуйте, &nbsp;</p><p>Вас приветствует {app_name}.</p><p>Hi, {order_name}, Спасибо за Шоппинг</p><p>Мы получили ваш запрос на покупку, мы \ скоро свяжемся!</p><p>Спасибо,</p><p>{app_name}</p><p>{order_url}</p>',
                    'tr' => "<p>Merhaba,&nbsp;</p><p>{app_name}'e hoş geldiniz.</p><p>Merhaba {order_name}, Alışveriş için teşekkür ederiz</p><p>Satın alma talebinizi aldık, kısa süre içinde sizinle iletişime geçeceğiz!</p><p>Teşekkürler,</p><p>{app_name}</p><p>{order_url}</p>",
                    'pt-br' => '<p>NAVE ÓRICA-Тутутутугальстугальский (app_name}).</p><p>Hi, {order_name}, пасссский</p><p>польстугальский потугальский (португальский), "скортугальский".</p><p>nome_do_appсссский!</p><p>{app_name}</p><p>{app_name}</p><p>{order_url}</p>',
                ],
            ],
            'Status Change' => [
                'subject' => 'Order Status',
                'lang' => [
                    'ar' => '<p> مرحبًا ، </p> <p> مرحبًا بك في {app_name}. </p> <p> طلبك هو {order_status}! </p> <p> مرحبًا {order_name} ، شكرًا لك على التسوق </p> <p> شكرًا ، </ p> <p> {app_name} </p> <p> {order_url} </p>',
                    'zh' => '<p>您好，</p><p>欢迎来到 {app_name}。</p><p>您的订单是 {order_status}！</p><p>您好{order_name}，感谢您的购物</p><p>谢谢，</p><p>{app_name}</p><p>{order_url}</p>',
                    'da' => '<p>Hej, &nbsp;</p><p>Velkommen til {app_name}.</p><p>Din ordre er {order_status}!</p><p>Hej {order_navn}, Tak for at Shopping</p><p>Tak,</p><p>{app_navn}</p><p>{order_url}</p>',
                    'de' => '<p>Hello, &nbsp;</p><p>Willkommen bei {app_name}.</p><p>Ihre Bestellung lautet {order_status}!</p><p>Hi {order_name}, Danke für Shopping</p><p>Danke,</p><p>{app_name}</p><p>{order_url}</p>',
                    'en' => '<p>Hello,&nbsp;</p><p>Welcome to {app_name}.</p><p>Your Order is {order_status}!</p><p>Hi {order_name}, Thank you for Shopping</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                    'es' => '<p>Hola, &nbsp;</p><p>Bienvenido a {app_name}.</p><p>Your Order is {order_status}!</p><p>Hi {order_name}, Thank you for Shopping</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                    'fr' => '<p>Bonjour, &nbsp;</p><p>Bienvenue dans {app_name}.</p><p>Votre commande est {order_status} !</p><p>Hi {order_name}, Thank you for Shopping</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                    'he' => '<p>שלום,&nbsp;</p><p>ברוך הבא ל {app_name}.</p><p>ההזמנה שלך היא {order_status}!</p><p>היי {order_name}, תודה על הקניות</p><p>תודה,</p><p>{app_name}</p><p>{order_url}</p>',
                    'pt' => '<p>SHOPPING CENTER-Тутутутугальстугальский (app_name}).</p><p>nomeia альстугальский (order_status}!</p><p>Hi {order_name}, Obrigado por Shopping</p><p>Obrigado,</p><p>{app_name}</p><p>{order_url}</p>',
                    'it' => '<p>Ciao, &nbsp;</p><p>Benvenuti in {app_name}.</p><p>Il tuo ordine è {order_status}!</p><p>Ciao {order_name}, Grazie per Shopping</p><p>Grazie,</p><p>{app_name}</p><p>{order_url}</p>',
                    'ja' => '<p>Ciao, &nbsp;</p><p>Benvenuti in {app_name}.</p><p>Il tuo ordine è {order_status}!</p><p>Ciao {order_name}, Grazie per Shopping</p><p>Grazie,</p><p>{app_name}</p><p>{order_url}</p>',
                    'nl' => '<p>Hallo, &nbsp;</p><p>Welkom bij {app_name}.</p><p>Uw bestelling is {order_status}!</p><p>Hi {order_name}, Dank u voor Winkelen</p><p>Bedankt,</p><p>{app_name}</p><p>{order_url}</p>',
                    'pl' => '<p>Hello, &nbsp;</p><p>Witamy w aplikacji {app_name}.</p><p>Twoje zamówienie to {order_status}!</p><p>Hi {order_name}, Dziękujemy za zakupy</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                    'ru' => '<p>Здравствуйте, &nbsp;</p><p>Вас приветствует {app_name}.</p><p>Ваш заказ-{order_status}!</p><p>Hi {order_name}, Thank you for Shopping</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                    'tr' => "<p>Merhaba,&nbsp;</p><p>{app_name}'e hoş geldiniz.</p><p>Siparişiniz {order_status}!</p><p>Merhaba {order_name}, Alışveriş için teşekkürler </p><p>Teşekkürler,</p><p>{app_name}</p><p>{order_url}</p>",
                    'pt-br' => '<p>SHOPPING CENTER-Тутутутугальстугальский (app_name}).</p><p>nomeia альстугальский (order_status}!</p><p>Hi {order_name}, Obrigado por Shopping</p><p>Obrigado,</p><p>{app_name}</p><p>{order_url}</p>',
                ],
            ],

            'Order Created For Owner' => [
                'subject' => 'Order Detail',
                'lang' => [
                    'ar' => '<p> مرحبًا ، </ p> <p> عزيزي {owner_name}. </p> <p> هذا أمر تأكيد {order_id} ضعه على <span style = \"font-size: 1rem؛\"> {order_date}. </span> </p> <p> شكرًا ، </ p> <p> {order_url} </p>',
                    'zh' =>  '<p>您好，</p><p>尊敬的{owner_name}。</p><p>这是确认订单 {order_id}，地点为<span style=\"font-size: 1rem;\" >{order_date}。</span></p><p>谢谢，</p><p>{order_url}</p>',
                    'da' => '<p>Hej </p><p>Kære {owner_name}.</p><p>Dette er ordrebekræftelse {order_id} sted på <span style=\"font-size: 1rem;\">{order_date}. </span></p><p>Tak,</p><p>{order_url}</p>',
                    'de' => '<p>Hallo, </p><p>Sehr geehrter {owner_name}.</p><p>Dies ist die Auftragsbestätigung {order_id}, die am <span style=\"font-size: 1rem;\">{order_date} aufgegeben wurde. </span></p><p>Danke,</p><p>{order_url}</p>',
                    'en' => '<p>Hello,&nbsp;</p><p>Dear {owner_name}.</p><p>This is Confirmation Order {order_id} place on&nbsp;<span style=\"font-size: 1rem;\">{order_date}.</span></p><p>Thanks,</p><p>{order_url}</p>',
                    'es' => '<p> Hola, </p> <p> Estimado {owner_name}. </p> <p> Este es el lugar de la orden de confirmación {order_id} en <span style = \"font-size: 1rem;\"> {order_date}. </span> </p> <p> Gracias, </p> <p> {order_url} </p>',
                    'fr' => '<p>Bonjour, </p><p>Cher {owner_name}.</p><p>Ceci est la commande de confirmation {order_id} passée le <span style=\"font-size: 1rem;\">{order_date}. </span></p><p>Merci,</p><p>{order_url}</p>',
                    'he' => '<p>שלום,&nbsp;</p><p>יָקָר {owner_name}.</p><p>זהו צו אישור {order_id} מקום על&nbsp;<span style=\"font-size: 1rem;\">{order_date}.</span></p><p>תודה,</p><p>{order_url}</p>',
                    'pt' => '<p> Térica-Dicas de Cadeia Pública de Тутутугальский (owner_name}). </p> <p> Тугальстугальстугальский (order_id} ний <span style = \" font-size: 1rem; \ "> {order_date}. </span> </p> <p> nome_do_chave de vida, </p> <p> {order_url} </p> <p> {order_url}',
                    'it' => '<p>Ciao, </p><p>Gentile {owner_name}.</p><p>Questo è l\'ordine di conferma {order_id} effettuato su <span style=\"font-size: 1rem;\">{order_date}. </span></p><p>Grazie,</p><p>{order_url}</p>',
                    'ja' => '<p>こんにちは、</ p> <p>親愛なる{owner_name}。</ p> <p>これは、<span style = \"font-size：1rem;\"> {order_date}の確認注文{order_id}の場所です。 </ span> </ p> <p>ありがとうございます</ p> <p> {order_url} </ p>',
                    'nl' => '<p>Hallo, </p><p>Beste {owner_name}.</p><p>Dit is de bevestigingsopdracht {order_id} die is geplaatst op <span style=\"font-size: 1rem;\">{order_date}. </span></p><p>Bedankt,</p><p>{order_url}</p>',
                    'pl' => '<p>Witaj, </p><p>Drogi {owner_name}.</p><p>To jest potwierdzenie zamówienia {order_id} złożone na <span style=\"font-size: 1rem;\">{order_date}. </span></p><p>Dzięki,</p><p>{order_url}</p>',
                    'ru' => '<p> Здравствуйте, </p> <p> Уважаемый {owner_name}. </p> <p> Это подтверждение заказа {order_id} на <span style = \"font-size: 1rem;\"> {order_date}. </span> </p> <p> Спасибо, </p> <p> {order_url} </p>',
                    'tr' => '<p>Merhaba,&nbsp;</p><p>Sevgili {owner_name}.</p><p>Bu, {order_id} siparişinin&nbsp;<span style=\"font-size: 1rem;\" üzerindeki Onay Siparişi yeridir. >{order_date}.</span></p><p>Teşekkürler,</p><p>{order_url}</p>',
                    'pt-br' => '<p> Térica-Dicas de Cadeia Pública de Тутутугальский (owner_name}). </p> <p> Тугальстугальстугальский (order_id} ний <span style = \" font-size: 1rem; \ "> {order_date}. </span> </p> <p> nome_do_chave de vida, </p> <p> {order_url} </p> <p> {order_url}',

                ],
            ],

            'Owner And Store Created' => [
                'subject' => 'Owner And Store Detail',
                'lang' => [
                    'ar' => '<p>مرحبًا,<b> {owner_name} </b>!</p> <p>مرحبًا بك في التطبيق الخاص بنا تفاصيل تسجيل الدخول الخاصة بـ <b> {app_name}</b> هو <br></p> <p><b>البريد الإلكتروني   : </b>{owner_email}</p> <p><b>كلمة المرور   : </b>{owner_password}</p> <p><b>عنوان url للتطبيق    : </b>{app_url}</p> <p><b>عنوان URL للمتجر: </b>{store_url}</p> <p>شكرا لتواصلك معنا،</p> <p>{app_name}</p>',
                    'da' => '<p>Hej,<b> {owner_name} </b>!</p> <p>Velkommen til vores app, hvor du kan logge ind <b> {app_name}</b> er <br></p> <p><b>E-mail   : </b>{owner_email}</p> <p><b>Adgangskode : </b>{owner_password}</p> <p><b>App url    : </b>{app_url}</p> <p><b>Butiks-url: </b>{store_url}</p> <p> Tak fordi du tog kontakt med os,</p> <p>{app_name}</p>',
                    'de' => '<p>Hallo,<b> {owner_name} </b>!</p> <p>Willkommen in unserer App für Ihre Login-Daten <b> {app_name}</b> ist <br></p> <p><b>Email   : </b>{owner_email}</p> <p><b>Passwort   : </b>{owner_password}</p> <p><b> App-URL    : </b>{app_url}</p> <p><b>Shop-URL: </b>{store_url}</p> <p>Danke, dass Sie sich mit uns verbunden haben,</p> <p>{app_name}</p>',
                    'en' => '<p>Hello,<b> {owner_name} </b>!</p> <p>Welcome to our app yore login detail for <b> {app_name}</b> is <br></p> <p><b>Email   : </b>{owner_email}</p> <p><b>Password   : </b>{owner_password}</p> <p><b>App url    : </b>{app_url}</p> <p><b>Store url    : </b>{store_url}</p> <p>Thank you for connecting with us,</p> <p>{app_name}</p>',
                    'es' => '<p>Hola,<b> {owner_name} </b>!</p> <p>Bienvenido a nuestra aplicación antaño detalles de inicio de sesión para <b> {app_name}</b> es <br></p> <p><b>Correo electrónico   : </b>{owner_email}</p> <p><b>Clave   : </b>{owner_password}</p> <p><b>URL de la aplicación  : </b>{app_url}</p> <p><b>URL de la tienda: </b>{store_url}</p> <p>Gracias por conectar con nosotras,</p> <p>{app_name}</p>',
                    'fr' => '<p>Bonjour,<b> {owner_name} </b>!</p> <p>Bienvenue sur notre application autrefois les informations de connexion pour <b> {app_name}</b> est <br></p> <p><b>E-mail   : </b>{owner_email}</p> <p><b>Mot de passe   : </b>{owner_password}</p> <p><b>URL de l\'application   : </b>{app_url}</p> <p><b>URL du magasin : </b>{store_url}</p> <p>Merci de nous avoir contacté,</p> <p>{app_name}</p>',
                    'it' => '<p>Ciao,<b> {owner_name} </b>!</p> <p>Benvenuto nella nostra app per i tuoi dati di accesso <b> {app_name}</b> è <br></p> <p><b>E-mail   : </b>{owner_email}</p> <p><b>Parola d\'ordine   : </b>{owner_password}</p> <p><b>URL dell\'app    : </b>{app_url}</p> <p><b>URL del negozio: </b>{store_url}</p> <p>Grazie per esserti connesso con noi,</p> <p>{app_name}</p>',
                    'ja' => '<p>こんにちは,<b> {owner_name} </b>!</p> <p>私たちのアプリのyoreログインの詳細へようこそ <b> {app_name}</b> は <br></p> <p><b>Eメール   : </b>{owner_email}</p> <p><b>パスワード   : </b>{owner_password}</p> <p><b>アプリのURL    : </b>{app_url}</p> <p><b>ストアの URL : </b>{store_url}</p> <p>ご連絡ありがとうございます,</p> <p>{app_name}</p>',
                    'nl' => '<p>Hallo,<b> {owner_name} </b>!</p> <p>Welkom bij de inloggegevens van onze app voor: <b> {app_name}</b> is <br></p> <p><b>E-mail   : </b>{owner_email}</p> <p><b>Wachtwoord   : </b>{owner_password}</p> <p><b>App-URL    : </b>{app_url}</p> <p><b>Winkel-URL: </b>{winkel_url</p> <p>Bedankt voor het contact met ons,</p> <p>{app_name}</p>',
                    'pl' => '<p>Witam,<b> {owner_name} </b>!</p> <p>Witamy w naszej aplikacji yore dane logowania do <b> {app_name}</b> jest <br></p> <p><b>E-mail   : </b>{owner_email}</p> <p><b>Hasło   : </b>{owner_password}</p> <p><b>URL aplikacji    : </b>{app_url}</p> <p><b>Adres sklepu: </b>{store_url}</p> <p>Dziękujemy za kontakt z nami,</p> <p>{app_name}</p>',
                    'ru' => '<p>Привет,<b> {owner_name} </b>!</p> <p>Добро пожаловать в наше приложение. <b> {app_name}</b> является <br></p> <p><b>Эл. адрес   : </b>{owner_email}</p> <p><b>Пароль   : </b>{owner_password}</p> <p><b>URL приложения    : </b>{app_url}</p> <p><b>URL-адрес магазина: </b>{store_url}</p> <p>Спасибо, что связались с нами,</p> <p>{app_name}</p>',
                    'pt' => '<p>Olá,<b> {owner_name} </b>!</p> <p>Bem-vindo ao nosso aplicativo antigo detalhe de login para <b> {app_name}</b> é <br></p> <p><b>E-mail   : </b>{owner_email}</p> <p><b>Senha   : </b>{owner_password}</p> <p><b>URL do aplicativo    : </b>{app_url}</p> <p><b>URL da loja: </b>{store_url}</p> <p>Obrigado por conectar com a gente,</p> <p>{app_name}</p>',
                    'tr' => '<p>Merhaba,<b> {owner_name} </b>!</p> <p>Uygulamamıza hoş geldiniz, eski <b> {app_name}</b> için giriş ayrıntısı <br></p> <p><b>E-posta : </b>{owner_email}</p> <p><b>Şifre : </b>{owner_password}</p> <p><b>Uygulama url : </b>{app_url}</p> <p><b>Mağaza URL si : </b>{store_url}</p> <p>Bizimle bağlantı kurduğunuz için teşekkür ederiz,</p> <p>{app_name}</p>',
                    'he' => '<p>שלום,<b> {owner_name} </b>!</p> <p>ברוך הבא לאפליקציה שלנו, פרטי ההתחברות של <b> {app_name}</b> הוא <br></p> <p><b>דוא"ל: </b>{owner_email}</p> <p><b>סיסמה: </b>{owner_password}</p> <p><b>כתובת אתר של אפליקציה: </b>{app_url}</p> <p><b>כתובת אתר של חנות: </b>{store_url}</p> <p>תודה שהתחברת אלינו,</p> <p>{app_name}</p>',
                    'zh' => '<p>您好，<b> {owner_name} </b>！</p> <p>欢迎使用我们的应用，<b> {app_name}</b> 的登录详细信息是<br></p> <p><b>电子邮件：</b>{owner_email}</p> <p><b>密码：</b>{owner_password}</p> <p><b>应用程序网址：</b>{app_url}</p> <p><b>商店网址：</b>{store_url}</p> <p>感谢您与我们联系，</p> <p>{app_name}</p>',
                    'pt-br' => '<p>Olá,<b> {owner_name} </b>!</p> <p>Bem-vindo ao nosso aplicativo antigo detalhe de login para <b> {app_name}</b> é <br></p> <p><b>E-mail   : </b>{owner_email}</p> <p><b>Senha   : </b>{owner_password}</p> <p><b>URL do aplicativo    : </b>{app_url}</p> <p><b>URL da loja: </b>{store_url}</p> <p>Obrigado por conectar com a gente,</p> <p>{app_name}</p>',

                ],
            ],

        ];

        $email = EmailTemplate::all();

        foreach ($email as $e) {
            foreach ($defaultTemplate[$e->name]['lang'] as $lang => $content) {
                $emailTemp = EmailTemplateLang::where('lang',$lang)->where('subject',$defaultTemplate[$e->name]['subject'])->first();
                if(!$emailTemp){
                    EmailTemplateLang::create(
                        [
                            'parent_id' => $e->id,
                            'lang' => $lang,
                            'subject' => $defaultTemplate[$e->name]['subject'],
                            'content' => $content,
                        ]
                    );
                }
            }
        }
    }
    private static $store_id = null;
    private static $customer = null;
    public static function CustomerAuthCheck($slug = null)
    {
        if ($slug == null) {
            $slug = \Request::segment(1);
        }
        $auth_customer = Auth::guard('customers')->user();
        if (!empty($auth_customer))
        {
            if(is_null(self::$store_id)){
                $store_id = Store::where('slug', $slug)->pluck('id')->first();
                self::$store_id = $store_id;
            }
            else{
                $store_id = self::$store_id;
            }
            if(is_null(self::$customer)){
                $customer = Customer::where('store_id', $store_id)->where('email', $auth_customer->email)->count();
                self::$customer = $customer;
            }
            else{
                $customer = self::$customer;
            }
            if ($customer > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public static function success_res($msg = "", $args = array())
    {

        $msg = $msg == "" ? "success" : $msg;
        $msg_id = 'success.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array(
            'flag' => 1,
            'msg' => $msg,
        );

        return $json;
    }

    public static function error_res($msg = "", $args = array())
    {
        $msg = $msg == "" ? "error" : $msg;
        $msg_id = 'error.' . $msg;
        $converted = \Lang::get($msg_id, $args);
        $msg = $msg_id == $converted ? $msg : $converted;
        $json = array(
            'flag' => 0,
            'msg' => $msg,
        );

        return $json;
    }

    public static function send_twilio_msg($to, $msg, $store)
    {

        try
        {
            $account_sid = $store->twilio_sid;

            $auth_token = $store->twilio_token;

            $twilio_number = $store->twilio_from;

            $client = new Client($account_sid, $auth_token);

            $client->messages->create($to, [
                'from' => $twilio_number,
                'body' => $msg]);
        } catch (\Exception $e) {
            return $e;
        }

    }

    public static function order_create_owner($order, $owner, $store)
    {
        try
        {
            $msg = __("Hello,
                    Dear" . ' ' . $owner->name . ' ' . ",
                    This is Confirmation Order " . ' ' . $order->order_id . "place on" . self::dateFormat($order->created_at) . "
                    Thanks,");

            Utility::send_twilio_msg($store->notification_number, $msg, $store);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function order_create_customer($order, $customer, $store)
    {
        try
        {
            $msg = __("Hello,
                Welcome to" . ' ' . $store->name . ' ' . ",
                Thank you for your purchase from" . ' ' . $store->name . ".
                Order Number:" . ' ' . $order->order_id . ".
                Order Date:" . ' ' . self::dateFormat($order->created_at));

            Utility::send_twilio_msg($customer->phone_number, $msg, $store);
        } catch (\Exception $e) {
            return $e;
        }

    }

    public static function status_change_customer($order, $customer, $store)
    {
        try
        {
            $msg = __("Hello,
            Welcome to" . ' ' . $store->name . ' ' . ",
            Your Order is" . ' ' . $order->status . ".
            Hi" . ' ' . $order->name . ", Thank you for Shopping.
            Thanks,
            " . $store->name);

            Utility::send_twilio_msg($customer->phone_number, $msg, $store);

        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function colorset()
    {
        if(is_null(self::$colorset)){
            if (\Auth::user()) {
                if (\Auth::user()->type == 'super admin') {
                    $user = \Auth::user();
                    $setting = DB::table('settings')->where('created_by', $user->id)->where('store_id', '0')->pluck('value', 'name')->toArray();
                } else {
                    $setting = DB::table('settings')->where('created_by', \Auth::user()->creatorId())->where('store_id', \Auth::user()->current_store)->pluck('value', 'name')->toArray();
                }
            } else {
                $user = User::where('type', 'super admin')->first();
                $setting = DB::table('settings')->where('created_by', 1)->pluck('value', 'name')->toArray();
            }
            if (!isset($setting['color'])) {
                $setting = Utility::settings();
            }
            self::$colorset = $setting;
        }
       return self::$colorset;
    }

    public static function GetLogo()
    {
        $setting = Utility::colorset();

        if (\Auth::user() && \Auth::user()->type != 'super admin') {

            if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on') {
                return Utility::getValByName('company_logo_light');
            } else {
                return Utility::getValByName('company_logo_dark');
            }
        } else {
            if (isset($setting['cust_darklayout']) && $setting['cust_darklayout'] == 'on') {
                return Utility::getValByName('logo_light');
            } else {
                return Utility::getValByName('logo_dark');
            }

        }

    }

    public static function upload_file($request, $key_name, $name, $path, $custom_validation = [])
    {
        try {
            $settings = Utility::getStorageSetting();

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com',
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';

                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';

                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes = !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }

                $file = $request->$key_name;

                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,

                    ];

                }

                $validator = \Validator::make($request->all(), [

                    $key_name => $validation,
                ]);
                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {
                        $request->$key_name->move(storage_path($path), $name);
                        $path = $path . $name;

                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;
                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );
                    }

                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path,
                    ];
                    return $res;
                }

            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }

        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function json_upload_file($json, $key_name, $name, $path, $custom_validation = [])
    {
        $request = [
            $key_name => $json[$key_name]
        ];

        try {
            $settings = Utility::getStorageSetting();

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com',
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';

                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';

                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes = !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }

                $file = $json[$key_name];


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,

                    ];

                }

                $validator = \Validator::make($request, [

                    $key_name => $validation,
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {
                        $json[$key_name]->move(storage_path($path), $name);
                        $path = $path . $name;

                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;
                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                    }

                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path,
                    ];
                    return $res;
                }

            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }

        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function multi_json_upload_file($json, $key_name, $name, $path, $custom_validation = [])
    {


        $request = [
            $key_name => $json
        ];

        try {
            $settings = Utility::getStorageSetting();

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com',
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';

                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';

                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes = !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }

                $file = $request[$key_name];


                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,

                    ];

                }

                $validator = \Validator::make($request, [

                    $key_name =>$validation
                ]);


                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {
                        $request[$key_name]->move(storage_path($path), $name);
                        $path = $path . $name;

                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                        // $path = $path.$name;
                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            $file,
                            $name
                        );

                    }

                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path,
                    ];
                    return $res;
                }

            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }

        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function get_file($path)
    {
        $storageSetting = Utility::StorageSettings();
        try {
            if ($storageSetting['storage_setting'] == 'wasabi') {
                config(
                    [
                        'filesystems.disks.wasabi.key' => $storageSetting['wasabi_key'],
                        'filesystems.disks.wasabi.secret' => $storageSetting['wasabi_secret'],
                        'filesystems.disks.wasabi.region' => $storageSetting['wasabi_region'],
                        'filesystems.disks.wasabi.bucket' => $storageSetting['wasabi_bucket'],
                        'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $storageSetting['wasabi_region'] . '.wasabisys.com',
                    ]
                );
                
                return \Storage::disk($storageSetting['storage_setting'])->url($path);

            } elseif ($storageSetting['storage_setting'] == 's3') {
                config(
                    [
                        'filesystems.disks.s3.key' => $storageSetting['s3_key'],
                        'filesystems.disks.s3.secret' => $storageSetting['s3_secret'],
                        'filesystems.disks.s3.region' => $storageSetting['s3_region'],
                        'filesystems.disks.s3.bucket' => $storageSetting['s3_bucket'],
                        'filesystems.disks.s3.use_path_style_endpoint' => false,
                    ]
                );
                return \Storage::disk($storageSetting['storage_setting'])->url($path);
            }
            return url('/').\Storage::disk($storageSetting['storage_setting'])->url($path);
        } catch (\Throwable $th) {
            return '';
        }
    }

    public static function getStorageSetting()
    {

        $data = DB::table('settings');
        $data = $data->where('created_by', '=', 1);
        $data = $data->get();
        $settings = [
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png",
            "local_storage_max_upload_size" => "",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url" => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

        return $settings;
    }

    public static function keyWiseUpload_file($request, $key_name, $name, $path, $data_key, $custom_validation = [])
    {

        $multifile = [
            $key_name => $request->file($key_name)[$data_key],
        ];
        try {
            $settings = Utility::getStorageSetting();

            if (!empty($settings['storage_setting'])) {

                if ($settings['storage_setting'] == 'wasabi') {

                    config(
                        [
                            'filesystems.disks.wasabi.key' => $settings['wasabi_key'],
                            'filesystems.disks.wasabi.secret' => $settings['wasabi_secret'],
                            'filesystems.disks.wasabi.region' => $settings['wasabi_region'],
                            'filesystems.disks.wasabi.bucket' => $settings['wasabi_bucket'],
                            'filesystems.disks.wasabi.endpoint' => 'https://s3.' . $settings['wasabi_region'] . '.wasabisys.com',
                        ]
                    );

                    $max_size = !empty($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : '2048';
                    $mimes = !empty($settings['wasabi_storage_validation']) ? $settings['wasabi_storage_validation'] : '';

                } else if ($settings['storage_setting'] == 's3') {
                    config(
                        [
                            'filesystems.disks.s3.key' => $settings['s3_key'],
                            'filesystems.disks.s3.secret' => $settings['s3_secret'],
                            'filesystems.disks.s3.region' => $settings['s3_region'],
                            'filesystems.disks.s3.bucket' => $settings['s3_bucket'],
                            'filesystems.disks.s3.use_path_style_endpoint' => false,
                        ]
                    );
                    $max_size = !empty($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : '2048';
                    $mimes = !empty($settings['s3_storage_validation']) ? $settings['s3_storage_validation'] : '';

                } else {
                    $max_size = !empty($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : '2048';

                    $mimes = !empty($settings['local_storage_validation']) ? $settings['local_storage_validation'] : '';
                }

                $file = $request->$key_name;

                if (count($custom_validation) > 0) {
                    $validation = $custom_validation;
                } else {

                    $validation = [
                        'mimes:' . $mimes,
                        'max:' . $max_size,
                    ];

                }

                $validator = \Validator::make($multifile, [
                    $key_name => $validation,
                ]);

                if ($validator->fails()) {
                    $res = [
                        'flag' => 0,
                        'msg' => $validator->messages()->first(),
                    ];
                    return $res;
                } else {

                    $name = $name;

                    if ($settings['storage_setting'] == 'local') {
                        $multifile[$key_name]->move(storage_path($path), $name);
                        $path = $path . $name;

                    } else if ($settings['storage_setting'] == 'wasabi') {

                        $path = \Storage::disk('wasabi')->putFileAs(
                            $path,
                            // $file,
                            $multifile[$key_name],
                            $name
                        );

                        // $path = $path.$name;

                    } else if ($settings['storage_setting'] == 's3') {

                        $path = \Storage::disk('s3')->putFileAs(
                            $path,
                            // $file,
                            $multifile[$key_name],
                            $name
                        );

                    }

                    $res = [
                        'flag' => 1,
                        'msg' => 'success',
                        'url' => $path,
                    ];
                    return $res;
                }

            } else {
                $res = [
                    'flag' => 0,
                    'msg' => __('Please set proper configuration for storage.'),
                ];
                return $res;
            }

        } catch (\Exception $e) {
            $res = [
                'flag' => 0,
                'msg' => $e->getMessage(),
            ];
            return $res;
        }
    }

    public static function StorageSettings()
    {
        if (is_null(self::$storageSetting)) {
            $data = DB::table('settings');

            $data->where('created_by', '=', 1);
            self::$storageSetting = $data->get();
        }
        $data = self::$storageSetting;
        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "currency_symbol_position" => "pre",
            "logo_dark" => "logo-dark.png",
            "logo_light" => "logo-light.png",
            "currency_symbol" => "",
            "currency" => "",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "invoice_prefix" => "#INV",
            "invoice_color" => "ffffff",
            "quote_template" => "template1",
            "quote_color" => "ffffff",
            "salesorder_template" => "template1",
            "salesorder_color" => "ffffff",
            "proposal_prefix" => "#PROP",
            "proposal_color" => "fffff",
            "bill_prefix" => "#BILL",
            "bill_color" => "fffff",
            "quote_prefix" => "#QUO",
            "salesorder_prefix" => "#SOP",
            "vender_prefix" => "#VEND",
            "footer_title" => "",
            "footer_notes" => "",
            "invoice_template" => "template1",
            "bill_template" => "template1",
            "proposal_template" => "template1",
            "default_language" => "en",
            "decimal_number" => "2",
            "tax_type" => "VAT",
            "shipping_display" => "on",
            "footer_link_1" => "Support",
            "footer_value_1" => "#",
            "footer_link_2" => "Terms",
            "footer_value_2" => "#",
            "footer_link_3" => "Privacy",
            "footer_value_3" => "#",
            "display_landing_page" => "on",
            "title_text" => "",
            "footer_text" => "",
            "company_logo_light" => "logo-light.png",
            "company_logo_dark" => "logo-dark.png",
            "company_favicon" => "",
            "gdpr_cookie" => "",
            "cookie_text" => "",
            "signup_button" => "on",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "color" => "theme-3",
            "SITE_RTL" => "off",
            "is_checkout_login_required" => "on",
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url" => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
            'mail_driver'=>'',
            'mail_host'=>'',
            'mail_port'=>'',
            'mail_username'=>'',
            'mail_password'=>'',
            'mail_encryption'=>'',
            'mail_from_name'=>'',
            'mail_from_address'=>'',
        ];

        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }

         
        return $settings;
    }
    public static function pixel_plateforms(){
        $plateforms = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter',
            'linkedin' => 'Linkedin',
            'pinterest' => 'Pinterest',
            'quora' => 'Quora',
            'bing' => 'Bing',
            'google-adwords' => 'Google Adwords',
            'google-analytics' => 'Google Analytics',
            'snapchat' => 'Snapchat',
            'tiktok' => 'Tiktok',
        ];

        return $plateforms;
    }
    public static function GetCacheSize()
    {
        $file_size = 0;
        foreach (\File::allFiles(storage_path('/framework')) as $file)
        {
            $file_size += $file->getSize();
        }
        $file_size = number_format($file_size / 1000000, 4);
        return $file_size;
    }
    public static function get_device_type($user_agent) {
        $mobile_regex = '/(?:phone|windows\s+phone|ipod|blackberry|(?:android|bb\d+|meego|silk|googlebot) .+? mobile|palm|windows\s+ce|opera mini|avantgo|mobilesafari|docomo)/i';
        $tablet_regex = '/(?:ipad|playbook|(?:android|bb\d+|meego|silk)(?! .+? mobile))/i';
    
        if(preg_match_all($mobile_regex, $user_agent)) {
            return 'mobile';
        } else {
    
            if(preg_match_all($tablet_regex, $user_agent)) {
                return 'tablet';
            } else {
                return 'desktop';
            }
    
        }
    }
    public static function webhook($module, $store_id)
    {
        $webhook = Webhook::where('module',$module)->where('store_id', '=', $store_id)->first();
        if(!empty($webhook)){
            $url = $webhook->url;
            $method = $webhook->method;
            $reference_url  = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            $data['method'] = $method;
            $data['reference_url'] = $reference_url;
            $data['url'] = $url;
            return $data;
        }
        return false;

    }

    public static function WebhookCall($url = null,$parameter = null , $method = '')
    {
        if(!empty($url) && !empty($parameter))
        {
            try {
                $curlHandle = curl_init($url);
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $parameter);
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlHandle, CURLOPT_CUSTOMREQUEST, $method);
                $curlResponse = curl_exec($curlHandle);
                curl_close($curlHandle);
                if(empty($curlResponse))
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }

            catch (\Throwable $th)
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
    public static function updateStorageLimit($owner_id, $image_size)
    {
        $image_size = number_format($image_size / 1048576, 2);
        $user   = User::find($owner_id);
        $plan   = Plan::find($user->plan);
        $total_storage = $user->storage_limit + $image_size;

        if($plan->storage_limit <= $total_storage && $plan->storage_limit != -1)
        {
            $error= 'Plan storage limit is over so please upgrade the plan.';
            return $error;
        }
        else{

            $user->storage_limit = $total_storage;
        }

        $user->save();
        return 1;

    }
    public static function changeStorageLimit($owner_id, $file_path)
    {
        $files =  \File::glob(storage_path($file_path));
        $fileSize = 0;
        foreach($files as $file){
            $fileSize += \File::size($file);
        }

        $image_size = number_format($fileSize / 1048576, 2);
        $user   = User::find($owner_id);
        $plan   = Plan::find($user->plan);
        $total_storage = $user->storage_limit - $image_size;
        $user->storage_limit = $total_storage;
        $user->save();

        $status = false;
        foreach($files as $key => $file)
        {
            if (\File::exists($file)) {
                \File::delete($file);
            }
        }
        return true;
    }
    public static function flagOfCountry(){
        $arr = [
            'ar' => '🇦🇪 ar',
            "zh" => "🇨🇳 zh",
            'da' => '🇩🇰 ad', 
           'de' => '🇩🇪 de',
            'es' => '🇪🇸 es',
            'fr' => '🇫🇷 fr',
           'it'	=>  '🇮🇹 it',
            'ja' => '🇯🇵 ja',
            'he' => '🇮🇱 he',
            'nl' => '🇳🇱 nl',
            'pl'=> '🇵🇱 pl',
            'ru' => '🇷🇺 ru',
            'pt' => '🇵🇹 pt',
            'en' => '🇮🇳 en',
            'tr' => '🇹🇷 tr',
            'pt-br' => '🇧🇷 pt-br',
        ];
        return $arr;
    }
    public static function langList(){
        $languages = [
            "ar" => "Arabic",
            "zh" => "Chinese",
            "da" => "Danish",
            "de" => "German",
            "en" => "English",
            "es" => "Spanish",
            "fr" => "French",
            "he" => "Hebrew",
            "it" => "Italian",
            "ja" => "Japanese",
            "nl" => "Dutch",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ru" => "Russian",
            "tr" => "Turkish",
            "pt-br"=>"Portuguese(Brazil)"
        ];
        return $languages;
    }
    public static function languagecreate(){
        $languages=Utility::langList();
        foreach($languages as $key => $lang)
        {
            $languageExist = Languages::where('code',$key)->first();
            if(empty($languageExist))
            {
                $language = new Languages();
                $language->code = $key;
                $language->fullName = $lang;
                $language->save();
            }
        }
    }
    public static function langSetting(){
        if (is_null(self::$settings)) {
            self::$settings = Utility::StorageSettings();
        }
    
        return self::$settings;
    }


    public static function settingsById($id)
    {
        $data     = DB::table('settings');
        $data     = $data->where('created_by', '=', $id);
        $data     = $data->get();
        $settings = [
            "site_currency" => "USD",
            "site_currency_symbol" => "$",
            "currency_symbol_position" => "pre",
            "logo_dark" => "logo-dark.png",
            "logo_light" => "logo-light.png",
            "currency_symbol" => "",
            "currency" => "",
            "site_date_format" => "M j, Y",
            "site_time_format" => "g:i A",
            "company_name" => "",
            "company_address" => "",
            "company_city" => "",
            "company_state" => "",
            "company_zipcode" => "",
            "company_country" => "",
            "company_telephone" => "",
            "company_email" => "",
            "company_email_from_name" => "",
            "invoice_prefix" => "#INV",
            "invoice_color" => "ffffff",
            "quote_template" => "template1",
            "quote_color" => "ffffff",
            "salesorder_template" => "template1",
            "salesorder_color" => "ffffff",
            "proposal_prefix" => "#PROP",
            "proposal_color" => "fffff",
            "bill_prefix" => "#BILL",
            "bill_color" => "fffff",
            "quote_prefix" => "#QUO",
            "salesorder_prefix" => "#SOP",
            "vender_prefix" => "#VEND",
            "footer_title" => "",
            "footer_notes" => "",
            "invoice_template" => "template1",
            "bill_template" => "template1",
            "proposal_template" => "template1",
            "default_language" => "en",
            "decimal_number" => "2",
            "tax_type" => "VAT",
            "shipping_display" => "on",
            "footer_link_1" => "Support",
            "footer_value_1" => "#",
            "footer_link_2" => "Terms",
            "footer_value_2" => "#",
            "footer_link_3" => "Privacy",
            "footer_value_3" => "#",
            "display_landing_page" => "on",
            "title_text" => "",
            "footer_text" => "",
            "company_logo_light" => "logo-light.png",
            "company_logo_dark" => "logo-dark.png",
            "company_favicon" => "",
            "gdpr_cookie" => "",
            "cookie_text" => "",
            "signup_button" => "on",
            "email_verification" => "on",
            "cust_theme_bg" => "on",
            "cust_darklayout" => "off",
            "color" => "theme-3",
            "SITE_RTL" => "off",
            "is_checkout_login_required" => "on",
            "storage_setting" => "local",
            "local_storage_validation" => "jpg,jpeg,png,xlsx,xls,csv,pdf",
            "local_storage_max_upload_size" => "2048000",
            "s3_key" => "",
            "s3_secret" => "",
            "s3_region" => "",
            "s3_bucket" => "",
            "s3_url" => "",
            "s3_endpoint" => "",
            "s3_max_upload_size" => "",
            "s3_storage_validation" => "",
            "wasabi_key" => "",
            "wasabi_secret" => "",
            "wasabi_region" => "",
            "wasabi_bucket" => "",
            "wasabi_url" => "",
            "wasabi_root" => "",
            "wasabi_max_upload_size" => "",
            "wasabi_storage_validation" => "",
            'chatgpt_key'=>'',
            'disable_lang'=>'',
            'mail_driver'=>'',
            'mail_host'=>'',
            'mail_port'=>'',
            'mail_username'=>'',
            'mail_password'=>'',
            'mail_encryption'=>'',
            'mail_from_name'=>'',
            'mail_from_address'=>'',
            'chatgpt_model_name'=>'',
        ];
        foreach ($data as $row) {
            $settings[$row->name] = $row->value;
        }
        return $settings;
    }

    public static function getSMTPDetails($user_id)
    {
        $settings = Utility::StorageSettings();
        if ($settings) {
            config([
                'mail.default'                   => isset($settings['mail_driver'])       ? $settings['mail_driver']       : '',
                'mail.mailers.smtp.host'         => isset($settings['mail_host'])         ? $settings['mail_host']         : '',
                'mail.mailers.smtp.port'         => isset($settings['mail_port'])         ? $settings['mail_port']         : '',
                'mail.mailers.smtp.encryption'   => isset($settings['mail_encryption'])   ? $settings['mail_encryption']   : '',
                'mail.mailers.smtp.username'     => isset($settings['mail_username'])     ? $settings['mail_username']     : '',
                'mail.mailers.smtp.password'     => isset($settings['mail_password'])     ? $settings['mail_password']     : '',
                'mail.from.address'              => isset($settings['mail_from_address']) ? $settings['mail_from_address'] : '',
                'mail.from.name'                 => isset($settings['mail_from_name'])    ? $settings['mail_from_name']    : '',
            ]);

            return $settings;
        } else {
            return redirect()->back()->with('Email SMTP settings does not configured so please contact to your site admin.');
        }
    }


    public static function emailTemplateLang($lang)
    {
        $defaultTemplate = [
            'Order Created' => [
                'subject' => 'Order Complete',
                'lang' => [
                    'en' => '<p>Hello,&nbsp;</p><p>Welcome to {app_name}.</p><p>Hi, {order_name}, Thank you for Shopping</p><p>We received your purchase request, we\'ll be in touch shortly!</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                ],
            ],
            'Status Change' => [
                'subject' => 'Order Status',
                'lang' => [
                    'en' => '<p>Hello,&nbsp;</p><p>Welcome to {app_name}.</p><p>Your Order is {order_status}!</p><p>Hi {order_name}, Thank you for Shopping</p><p>Thanks,</p><p>{app_name}</p><p>{order_url}</p>',
                ],
            ],

            'Order Created For Owner' => [
                'subject' => 'Order Detail',
                'lang' => [
                    'en' => '<p>Hello,&nbsp;</p><p>Dear {owner_name}.</p><p>This is Confirmation Order {order_id} place on&nbsp;<span style=\"font-size: 1rem;\">{order_date}.</span></p><p>Thanks,</p><p>{order_url}</p>',

                ],
            ],

            'Owner And Store Created' => [
                'subject' => 'Owner And Store Detail',
                'lang' => [
                    'en' => '<p>Hello,<b> {owner_name} </b>!</p> <p>Welcome to our app yore login detail for <b> {app_name}</b> is <br></p> <p><b>Email   : </b>{owner_email}</p> <p><b>Password   : </b>{owner_password}</p> <p><b>App url    : </b>{app_url}</p> <p><b>Store url    : </b>{store_url}</p> <p>Thank you for connecting with us,</p> <p>{app_name}</p>',

                ],
            ],

        ];
        $email = EmailTemplate::all();
        foreach ($email as $e) {
            foreach ($defaultTemplate[$e->name]['lang'] as  $content) {
                $emailNoti = EmailTemplateLang::where('parent_id', $e->id)->where('lang', $lang)->count();
                if ($emailNoti == 0) {
                    EmailTemplateLang::create(
                        [
                           'parent_id' => $e->id,
                           'lang' => $lang,
                           'subject' => $defaultTemplate[$e->name]['subject'],
                           'content' => $content,
                        ]
                    );
                }
            }
        }
    }


    public static function referralTransaction($plan , $company= '')
    {
        if($company != '')
        {
            $objUser = $company;
        }
        else
        {            
            $objUser = \Auth::user();
        }

        $user = ReferralTransaction::where('company_id' , $objUser->id)->first();

        $referralSetting = ReferralSetting::where('created_by' , 1)->first();

        if($objUser->used_referral_code != 0 && $user == null && (isset($referralSetting) && $referralSetting->is_enable == 1))
        {
            $transaction         = new ReferralTransaction();
            $transaction->company_id = $objUser->id;
            $transaction->plan_id = $plan->id;
            $transaction->plan_price = $plan->price;
            $transaction->commission = $referralSetting->percentage;
            $transaction->referral_code = $objUser->used_referral_code;
            $transaction->save();

            $commissionAmount  = ($plan->price * $referralSetting->percentage)/100;
            $user = User::where('referral_code' , $objUser->used_referral_code)->first();
    
            $user->commission_amount = $user->commission_amount + $commissionAmount;
            $user->save();
        }
    }

}
