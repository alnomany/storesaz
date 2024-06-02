<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Store;
use App\Models\UserStore;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Utility;
use App\Models\BlogSocial;
use Illuminate\Support\Facades\DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrPermissions =[
            [
                "name" => "Manage Dashboard",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ], [
                "name" => "Manage Store Analytics",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage User",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create User",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit User",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete User",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Role",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Role",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Role",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Role",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Orders",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Show Orders",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Orders",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Products",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Products",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Products",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Show Products",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Products",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Variants",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Variants",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Variants",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Product category",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Product category",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Product category",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Product category",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Product Tax",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Product Tax",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Create Ratting",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Product Tax",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Product Tax",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Ratting",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Ratting",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Product Coupan",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Product Coupan",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Show Product Coupan",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Product Coupan",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Product Coupan",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Subscriber",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Subscriber",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Subscriber",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Location",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Location",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Location",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Location",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Shipping",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Shipping",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Shipping",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Shipping",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Custom Page",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Custom Page",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Custom Page",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Custom Page",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Blog",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Blog",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Blog",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Blog",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Customers",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Show Customers",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Settings",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Change Store",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Language",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Language",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Language",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Store",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Store",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Store",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Store",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Reset Password",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Upgrade Plans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Coupans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Coupans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Delete Coupans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Coupans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Show Coupans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Plans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Create Plans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Plans",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Manage Email Template",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name" => "Edit Email Template",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Manage Plan Order",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Manage Plan Request",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Manage Pos",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Create Pos",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Manage Themes",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
            [
                "name"=>"Edit Themes",
                "guard_name" => "web",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s'),
            ],
        ];
        Permission::insert($arrPermissions);
        
            $superAdminRole        = Role::create(
                [
                    'name' => 'super admin',
                    'created_by' => 0,
                ]
            );
       
        $superAdminPermissions = [
            ["name"=>"Manage Dashboard"],
            ["name"=>"Manage Language"],
            ["name"=>"Create Language"],
            ["name"=>"Delete Language"],
            ["name"=>"Manage Plans"],
            ["name"=>"Create Plans"],
            ["name"=>"Edit Plans"],
            ["name"=>"Manage Store"],
            ["name"=>"Create Store"],
            ["name"=>"Delete Store"],
            ["name"=>"Edit Store"],
            ["name"=>"Reset Password"],
            ["name"=>"Upgrade Plans"],
            ["name"=>"Manage Coupans"],
            ["name"=>"Create Coupans"],
            ["name"=>"Delete Coupans"],
            ["name"=>"Edit Coupans"],
            ["name"=>"Show Coupans"],
            ["name"=>"Manage Email Template"],
            ["name"=>"Edit Email Template"],
            ["name"=>"Manage Settings"],
            ["name"=>"Manage Plan Order"],
            ["name"=>"Manage Plan Request"],
        ];
        $superAdminRole->givePermissionTo($superAdminPermissions);
        $superAdmin              = User::create(
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@example.com',
                'email_verified_at' => date("Y-m-d H:i:s"),
                'password' => Hash::make('1234'),
                'type' => 'super admin',
                'lang' => 'en',
                'created_by' => 0,
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        $ownerRole        = Role::create(
            [
                'name' => 'Owner',
                'created_by' => $superAdmin->id,
            ]
        );
        $ownerPermissions = [
            ["name"=>"Manage Dashboard"],
            ["name"=>"Manage Store Analytics"],
            ["name"=>"Manage Role"],
            ["name"=>"Create Role"],
            ["name"=>"Delete Role"],
            ["name"=>"Edit Role"],
            ["name"=>"Manage User"],
            ["name"=>"Create User"],
            ["name"=>"Delete User"],
            ["name"=>"Edit User"],
            ["name"=>"Reset Password"],
            ["name"=>"Manage Orders"],
            ["name"=>"Show Orders"],
            ["name"=>"Delete Orders"],
            ["name"=>"Manage Products"],
            ["name"=>"Create Products"],
            ["name"=>"Show Products"],
            ["name"=>"Delete Products"],
            ["name"=>"Edit Products"],
            ["name"=>"Manage Product category"],
            ["name"=>"Create Product category"],
            ["name"=>"Delete Product category"],
            ["name"=>"Edit Product category"],
            ["name"=>"Create Variants"],
            ["name"=>"Edit Variants"],
            ["name"=>"Delete Variants"],
            ["name"=>"Manage Plans"],
            ["name"=>"Manage Product Tax"],
            ["name"=>"Create Product Tax"],
            ["name"=>"Delete Product Tax"],
            ["name"=>"Edit Product Tax"],
            ["name"=>"Create Ratting"],
            ["name"=>"Edit Ratting"],
            ["name"=>"Delete Ratting"],
            ["name"=>"Manage Product Coupan"],
            ["name"=>"Create Product Coupan"],
            ["name"=>"Show Product Coupan"],
            ["name"=>"Delete Product Coupan"],
            ["name"=>"Edit Product Coupan"],
            ["name"=>"Manage Subscriber"],
            ["name"=>"Create Subscriber"],
            ["name"=>"Delete Subscriber"],
            ["name"=>"Manage Location"],
            ["name"=>"Create Location"],
            ["name"=>"Delete Location"],
            ["name"=>"Edit Location"],
            ["name"=>"Manage Shipping"],
            ["name"=>"Create Shipping"],
            ["name"=>"Delete Shipping"],
            ["name"=>"Edit Shipping"],
            ["name"=>"Manage Custom Page"],
            ["name"=>"Create Custom Page"],
            ["name"=>"Delete Custom Page"],
            ["name"=>"Edit Custom Page"],
            ["name"=>"Manage Blog"],
            ["name"=>"Create Blog"],
            ["name"=>"Delete Blog"],
            ["name"=>"Edit Blog"],
            ["name"=>"Manage Customers"],
            ["name"=>"Show Customers"],
            ["name"=>"Manage Settings"],
            ["name"=>"Create Store"],
            ["name"=>"Manage Store"],
            ["name"=>"Manage Change Store"],
            ["name"=>"Manage Pos"],
            ["name"=>"Create Pos"],
            ["name"=>"Manage Themes"],
            ["name"=>"Edit Themes"],
        ];
        $ownerRole->givePermissionTo($ownerPermissions);
        $admin = User::create(
            [
                'name' => 'Owner',
                'email' => 'owner@example.com',
                'email_verified_at' => date("Y-m-d H:i:s"),
                'password' => Hash::make('1234'),
                'type' => 'Owner',
                'lang' => 'en',
                'created_by' => $superAdmin->id,
                'referral_code' => rand(100000 , 999999),
            ]
        );
        $admin->assignRole($ownerRole);
        $objStore             = Store::create(
            [
                'name' => 'My Store',
                'email' => 'owner@example.com',
                'enable_storelink' => 'on',
                'content' => 'Hi,
        *Welcome to* {store_name},
        Your order is confirmed & your order no. is {order_no}
        Your order detail is:
        Name : {customer_name}
        Address : {billing_address} {billing_city} , {shipping_address} {shipping_city}
        ~~~~~~~~~~~~~~~~
        {item_variable}
        ~~~~~~~~~~~~~~~~
        Qty Total : {qty_total}
        Sub Total : {sub_total}
        Discount Price : {discount_amount}
        Shipping Price : {shipping_amount}
        Tax : {total_tax}
        Total : {final_total}
        ~~~~~~~~~~~~~~~~~~
        To collect the order you need to show the receipt at the counter.
        Thanks {store_name}
        ',
                'item_variable' => '{sku} : {quantity} x {product_name} - {variant_name} + {item_tax} = {item_total}',
                'store_theme' => 'theme1-v1',
                'theme_dir' => 'theme1',
                'enable_rating' => 'on',
                'logo' => 'logo.png',
                'created_by' => $admin->id,
            ]
        );
        $admin->current_store = $objStore->id;
        $admin->save();

        UserStore::create(
            [
                'user_id' => $admin->id,
                'store_id' => $objStore->id,
                'permission' => 'Owner',
            ]
        );
       BlogSocial::create(
            [
                'enable_social_button' => 'on',
                'enable_email' => 'on',
                'enable_twitter' => 'on',
                'enable_facebook' => 'on',
                'enable_googleplus' => 'on',
                'enable_linkedIn' => 'on',
                'enable_pinterest' => 'on',
                'enable_stumbleupon' => 'on',
                'enable_whatsapp' => 'on',
                'store_id' => $objStore->id,
                'created_by' => $admin->id,
            ]
        );

        Utility::defaultEmail();
        Utility::userDefaultData();
        Utility::languagecreate();

        $data = [
            ['name'=>'local_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'wasabi_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'s3_storage_validation', 'value'=> 'jpg,jpeg,png,xlsx,xls,csv,pdf', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'local_storage_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'wasabi_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'s3_max_upload_size', 'value'=> 2048000, 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()],
            ['name'=>'storage_setting', 'value'=> 'local', 'created_by'=> 1, 'created_at'=> now(), 'updated_at'=> now()]
        ];

        DB::table('settings')->insert($data);  
    }
}
