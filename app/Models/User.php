<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'type',
        'acl',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
    /**
     * 创建数据时，如果type没有传值 就用默认 user 值
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->type) {
                $model->type = 'user';
            }
        });
    }
    /**
    * 通过 type 判断 user 是会员 admin是管理员 seller是商家  store是门店
    */
    public function getTypeLabelAttribute()
    {
        $label = '会员';
        switch ($this->type) {
            case 'user':
                $label = '会员';
                break;
            case 'admin':
                $label = '管理员';
                break;
            case 'seller':
                $label = '商家';
                break;
            case 'store':
                $label = '门店';
        }
        return $label;
    }
    /**
     * 通过 type 显示不同的颜色
     */
    public function getTypeColorAttribute()
    {
        $color = 'default';
        switch ($this->type) {
            case 'user':
                $color = 'primary';
                break;
            case 'admin':
                $color = 'danger';
                break;
            case 'seller':
                $color = 'warning';
                break;
            case 'store':
                $color = 'success';
        }
        return $color;
    }
    /**
     * 获取用户token
     */
    public function getToken()
    {
        $device_name = request()->input('device_name') ?: 'web';
        $token = $this->createToken($device_name, ['server:update']);
        return $token->plainTextToken;
    }
    /**
     * 关联oauth表，返回所有记录
     */
    public function oauths()
    {
        return $this->hasMany(Oauth::class, 'user_id', 'id');
    }
    /**
     * 关联Oauth,返回一条记录
     */
    public function oauth()
    {
        $type = request()->input('type') ?: 'weixin';
        return $this->hasOne(Oauth::class, 'user_id', 'id')->where('type', $type);
    }
    /**
     * 判断email格式是否正确
     */
    public static function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    /**
     * 使用email password登录
     */
    public static function loginByEmail($email, $password)
    {
        if (empty($email)) {
            return ['code' => 250,'message' => '邮箱不能为空'];
        }
        if (empty($password)) {
            return ['code' => 250,'message' => '密码不能为空'];
        }
        if (!self::isEmail($email)) {
            return ['code' => 250,'message' => '邮箱格式不正确'];
        }
        $user = self::where('email', $email)->first();
        if (!$user || !password_verify($password, $user->password)) {
            return ['code' => 250,'message' => '帐号或密码错误'];
        }
        $token = $user->getToken();
        $data = [
            'token' => $token,
            'user_id' => $user->id,
            'type' => $user->type,
        ];
        return ['code' => 0,'message' => '登录成功','data' => $data];
    }
    /**
     * 使用 email 及 phone注册帐号
     */
    public static function regByEmailAndPhone($email, $phone, $password, $type = 'user')
    {
        //查寻手机号或邮箱是否已注册
        $user = self::where('phone', $phone)->orWhere('email', $email)->first();
        if ($user) {
            return ['code' => 250,'message' => '邮箱或手机号已注册'];
        }
        $user = self::create([
            'email' => $email,
            'phone' => $phone,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'type' => $type,
            'name' => '用户'.rand(100000, 999999),
        ]);
        $token = $user->getToken();
        $data = [
            'token' => $token,
            'user_id' => $user->id,
        ];
        return ['code' => 0,'message' => '注册成功','data' => $data];
    }
    /**
     * 使用phone 注册帐号
     */
    public static function regByPhone($phone, $type = 'user')
    {
        $user = self::where('phone', $phone)->first();
        if ($user) {
            return ['code' => 250,'message' => '手机号已注册'];
        }
        $user = self::create([
            'phone' => $phone,
            'type' => $type,
            'name' => '用户'.rand(100000, 999999),
        ]);
        $token = $user->getToken();
        $data = [
            'token' => $token,
            'user_id' => $user->id,
        ];
        return ['code' => 0,'message' => '注册成功','data' => $data];
    }
    /**
     * 使用email password注册帐号
     * 1.判断email格式是否正确
     * 2.判断email是否已注册
     * 3.注册帐号
     */
    public static function regByEmail($email, $password, $type = 'user')
    {
        if (empty($email)) {
            return ['code' => 250,'message' => '邮箱不能为空'];
        }
        if (empty($password)) {
            return ['code' => 250,'message' => '密码不能为空'];
        }
        if (!self::isEmail($email)) {
            return ['code' => 250,'message' => '邮箱格式不正确'];
        }
        /**
         * password不能太简单，也不能太长，最长16位，最短6人位，包含大写字母、数字、特殊符号才行
         */
        $rule = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).{6,16}$/';
        if (!preg_match($rule, $password)) {
            return ['code' => 250,'message' => '密码强度不够，需要包含大写字母、数字、特殊符号且以字母开头'];
        }
        $user = self::where('email', $email)->first();
        if ($user) {
            return ['code' => 250,'message' => '邮箱已注册'];
        }
        $user = self::create([
            'email' => $email,
            'type' => $type,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'name' => '用户'.rand(100000, 999999),
        ]);
        $token = $user->getToken();
        $data = [
            'token' => $token,
            'user_id' => $user->id,
        ];
        return ['code' => 0,'message' => '注册成功','data' => $data];
    }
    /**
     * 生成随机密码
     */
    public static function generateRandomPassword($length = 12)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-';
        $password = '';

        // 确保密码包含至少一个大写字母、小写字母、数字和特殊字符
        $password .= chr(random_int(65, 90)); // 大写字母
        $password .= chr(random_int(97, 122)); // 小写字母
        $password .= $characters[random_int(33, 43)]; // 数字
        $password .= $characters[random_int(44, 47)]; // 特殊字符

        // 填充剩余的字符，直到达到所需长度
        for ($i = 4; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        // 打乱密码数组，以避免可预测的模式
        $passwordArray = str_split($password);
        shuffle($passwordArray);
        $password = implode('', $passwordArray);

        return $password;
    }
    public function isAdmin()
    {
        if ($this->type == 'admin') {
            if ($this->id == 1) {
                return true;
            }
        }
    }
    /**
     * 更新数据时判断acl有没有值，保存到UserAcl表
     */
    public function save(array $options = [])
    {
        $acl = $this->acl;
        UserAcl::where(['user_id' => $this->id])->delete();
        if ($acl) {
            foreach ($acl as $k => $v) {
                UserAcl::create([
                    'user_id' => $this->id,
                    'acl' => $v,
                ]);
            }
        }
        //删除acl字段
        unset($this->acl);
        return parent::save($options);
    }
    /**
     * 关联UserAcl表
     */
    public function userAcl()
    {
        return $this->hasMany(UserAcl::class, 'user_id', 'id');
    }
}
