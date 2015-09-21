<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;


class User extends Eloquent implements UserInterface, RemindableInterface
{

    use SoftDeletingTrait;

    function getRememberTokenName()
    {
    }

    function getRememberToken()
    {
    }

    function getReminderEmail()
    {
    }

    function setRememberToken($token)
    {
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $table = 'users';
    protected $fillable = ['name', 'password', 'email', 'username', 'role', 'created_by', 'pp', 'bio', 'social', 'views'];
    protected $visible = ['name', 'email', 'role', 'created_by', 'pp', 'bio', 'social', 'views', 'id'];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

}
