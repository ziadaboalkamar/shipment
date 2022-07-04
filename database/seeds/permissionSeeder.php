<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\User;
class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $perms=[
        ['name'=>'homePage-shipment'  ,  'display_name'=>'اجماليات الشحنات' ,'sort_no'=>'1'],
        ['name'=>'index-shipment'  ,  'display_name'=>'عرض الشحنات' ,'sort_no'=>'1'],
        ['name'=>'new-shipment'  ,  'display_name'=>'اضافة شحنة' ,'sort_no'=>'1'],
        ['name'=>'update-shipment'  ,  'display_name'=>'تعديل الشحنات' ,'sort_no'=>'1'],
        ['name'=>'status-shipmnt'  ,  'display_name'=>'حالات الشحنات' ,'sort_no'=>'1'],
        ['name'=>'print-shipment'  ,  'display_name'=>'طباعة الشحنات' ,'sort_no'=>'1'],
        ['name'=>'search-shipment'  ,  'display_name'=>'البحث في الشحنات' ,'sort_no'=>'1'],
        ['name'=>'archive-shipment'  ,  'display_name'=>'ارشيف الشحنات' ,'sort_no'=>'1'],
        ['name'=>'t7weelQr-shipment'  ,  'display_name'=>'تحويل حالة الشحنات' ,'sort_no'=>'1'],
        ['name'=>'tasleemToMandoubtaslim-shipment'  ,  'display_name'=>'تسليم الشحنه الى مندوب التسليم' ,'sort_no'=>'1'],
        // ['name'=>'read-'  ,  'display_name'=>'' ,'sort_no'=>'1'],
        // ['name'=>'read-'  ,  'display_name'=>'' ,'sort_no'=>'1'],
        // ['name'=>'read-'  ,  'display_name'=>'' ,'sort_no'=>'1'],
        ['name'=>'export-frou3'  ,  'display_name'=>'الشحنات الصادرة الى فرق' ,'sort_no'=>'2'],
        ['name'=>'import-frou3'  ,  'display_name'=>'الشحنات الواردة من فرع' ,'sort_no'=>'2'],
        ['name'=>'t7welSho7natManual-frou3'  ,  'display_name'=>'تحويل الشحنات الى فرع يدويا' ,'sort_no'=>'2'],
        ['name'=>'t7welsho7natQr-frou3'  ,  'display_name'=>'تحويل الشحنات الى فرع باستخدام qr' ,'sort_no'=>'2'],
        ['name'=>'acceptT7welsho7natQr-frou3'  ,  'display_name'=>'الموافقة على تحويل الشحنات الواردة من الفروع' ,'sort_no'=>'2'],

        ['name'=>'t7welRag3Manual-frou3'  ,  'display_name'=>'تحويل الرواجع الى فرع يدويا' ,'sort_no'=>'2'],
        ['name'=>'t7welRag3Qr-frou3'  ,  'display_name'=>'تحويل الرواجع الى فرع باستخدام qr' ,'sort_no'=>'2'],
        ['name'=>'acceptT7welRag3Qr-frou3'  ,  'display_name'=>'الموافقة على تحويل الرواجع الواردة من الفروع' ,'sort_no'=>'2'],

        ['name'=>'notMosadad-frou3'  ,  'display_name'=>'الشحنات الغير مسدده للفرع' ,'sort_no'=>'2'],
        ['name'=>'mosadad-frou3'  ,  'display_name'=>'الشحنات  المسدده للفرع' ,'sort_no'=>'2'],

        ['name'=>'notMosadad3amel-accounting'  ,  'display_name'=>'الشحنات  الغير مسدده للعميل' ,'sort_no'=>'3'],
        ['name'=>'mosadad3amel-accounting'  ,  'display_name'=>'الشحنات  المسدده للعميل' ,'sort_no'=>'3'],
        ['name'=>'mosadadMandoubTaslem-accounting'  ,  'display_name'=>'الشحنات  المسدده لمنودب التسليم' ,'sort_no'=>'3'],
        ['name'=>'notMosadadMandoubTaslem-accounting'  ,  'display_name'=>'الشحنات  الغير مسدده لمنودب التسليم' ,'sort_no'=>'3'],
        ['name'=>'notMosadadMandoubEstlam-accounting'  ,  'display_name'=>'الشحنات الغير مسدده لمنودب الاستلام' ,'sort_no'=>'3'],
        ['name'=>'mosadadMandoubEstlam-accounting'  ,  'display_name'=>'الشحنات  المسدده لمنودب الاستلام' ,'sort_no'=>'3'],

        ['name'=>'companyDefinations-definations'  ,  'display_name'=>'تعريف الشركة' ,'sort_no'=>'4'],
        ['name'=>'addManatek-definations'  ,  'display_name'=>'اضافة تسعير المناطق' ,'sort_no'=>'4'],
        ['name'=>'addBranches-definations'  ,  'display_name'=>'اضافة الفروع' ,'sort_no'=>'4'],

        ['name'=>'tas3irMandoub-definations'  ,  'display_name'=>'تسعير المندوبين' ,'sort_no'=>'5'],
        ['name'=>'tas3ir3amel5as-definations'  ,  'display_name'=>'تسعير العميل الخاص' ,'sort_no'=>'5'],

        ['name'=>'add3amel-userDefinations'  ,  'display_name'=>'اضافة العملاء' ,'sort_no'=>'6'],
        ['name'=>'addmandoub-userDefinations'  ,  'display_name'=>'اضافة المندوبين' ,'sort_no'=>'6'],
        ['name'=>'adduser-userDefinations'  ,  'display_name'=>'اضافة المستخدمين' ,'sort_no'=>'6'],
        ['name'=>'registrationRequest-userDefinations'  ,  'display_name'=>'طلبات التسجيل' ,'sort_no'=>'6'],
        ['name'=>'commertialName-userDefinations'  ,  'display_name'=>'تعديل الاسماء التجارية ' ,'sort_no'=>'6'],

        ['name'=>'permitions-setting'  ,  'display_name'=>'صلاحيات المستخدمين' ,'sort_no'=>'7'],
        ['name'=>'setting-setting'  ,  'display_name'=>'اعدادات الموقع ' ,'sort_no'=>'7'],

         



    ];
    public function run()
    {
        DB::table('permissions')->delete();
        DB::table('roles')->delete();
        DB::table('permission_role')->delete();
        DB::table('permission_user')->delete();
        DB::table('role_user')->delete();
        User::where('username' , 'Superuser')->delete();
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'User Administrator', // optional
            'description' => 'User is allowed to manage and edit other users', // optional
        ]);
        
        foreach($this->perms as $perm){
            $p =new Permission();
            $p->name= $perm['name'];
            $p->display_name= $perm['display_name'];
            // $p->guard_name= 'web';
           $p->sort_no= $perm['sort_no'];
            $p->save();
            $admin->attachPermission($p);
        }
        $superUser=new User();
        $superUser->name_ = 'super Admin';
        $superUser->type_ = 'موظف';
        $superUser->status_ = 1;
        $superUser->branch = 'الفرع الرئيسى';
        $superUser->username = 'Superuser';
        $superUser->password = '123@super';
        $superUser->mo7fza = 'بغداد';
        $superUser->mantqa = 'مركز';
        $superUser->phone_ = '';
        $superUser->save();
        $superUser->attachRole($admin);
    }
}
