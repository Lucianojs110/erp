<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the business that owns the user.
     */
    public function business()
    {
        return $this->belongsTo(\App\Business::class);
    }

    /**
     * The contact the user has access to.
     * Applied only when selected_contacts is true for a user in
     * users table
     */
    public function contactAccess()
    {
        return $this->belongsToMany(\App\Contact::class, 'user_contact_access');
    }

    /**
     * Creates a new user based on the input provided.
     *
     * @return object
     */
    public static function create_user($details)
    {
        $user = User::create([
            'surname' => $details['surname'],
            'first_name' => $details['first_name'],
            'last_name' => $details['last_name'],
            'username' => $details['username'],
            'email' => $details['email'],
            'password' => bcrypt($details['password']),
            'language' => !empty($details['language']) ? $details['language'] : 'en'
        ]);

        return $user;
    }

    /**
     * Gives locations permitted for the logged in user
     *
     * @return string or array
     */
    public function permitted_locations()
    {
        $permitted = $this->getAllPermissions()
            ->pluck('name')
            ->filter(function ($perm) {
                return str_starts_with($perm, 'location.');
            })
            ->map(function ($perm) {
                return (int) str_replace('location.', '', $perm);
            })
            ->values()
            ->all();

        return $this->can('access_all_locations') ? 'all' : $permitted;
    }


    /**
     * Returns if a user can access the input location
     *
     * @param: int $location_id
     * @return boolean
     */
    public static function can_access_this_location($location_id)
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        $permitted_locations = $user->permitted_locations();

        if ($permitted_locations == 'all' || in_array($location_id, $permitted_locations)) {
            return true;
        }

        return false;
    }

    /**
     * Return list of users dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     * @param $include_commission_agents = false (boolean)
     *
     * @return array users
     */
    public static function forDropdown($business_id, $prepend_none = true, $include_commission_agents = false, $prepend_all = false)
    {
        $cacheKey = "users_dropdown_{$business_id}_{$include_commission_agents}_{$prepend_none}_{$prepend_all}";
        $users = cache()->remember($cacheKey, 54000, function () use ($business_id, $include_commission_agents, $prepend_none, $prepend_all) {
            $query = User::where('business_id', $business_id);
            if (!$include_commission_agents) {
                $query->where('is_cmmsn_agnt', 0);
            }

            $all_users = $query->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"));

            $users = $all_users->pluck('full_name', 'id');

            //Prepend none
            if ($prepend_none) {
                $users = $users->prepend(__('lang_v1.none'), '');
            }

            //Prepend all
            if ($prepend_all) {
                $users = $users->prepend(__('lang_v1.all'), '');
            }

            return $users;
        });

        return $users;
    }

    /**
     * Return list of sales commission agents dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     *
     * @return array users
     */
    public static function saleCommissionAgentsDropdown($business_id, $prepend_none = true)
    {
        $all_cmmsn_agnts = User::where('business_id', $business_id)
            ->where('is_cmmsn_agnt', 1)
            ->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"));

        $users = $all_cmmsn_agnts->pluck('full_name', 'id');

        //Prepend none
        if ($prepend_none) {
            $users = $users->prepend(__('lang_v1.none'), '');
        }

        return $users;
    }

    /**
     * Return list of users dropdown for a business
     *
     * @param $business_id int
     * @param $prepend_none = true (boolean)
     *
     * @return array users
     */
    public static function allUsersDropdown($business_id, $prepend_none = true)
    {
        $cacheKey = "all_users_dropdown_{$business_id}_{$prepend_none}";
        $users = cache()->remember($cacheKey, 54000, function () use ($business_id, $prepend_none) {
            $all_users = User::where('business_id', $business_id)
                ->select('id', DB::raw("CONCAT(COALESCE(surname, ''),' ',COALESCE(first_name, ''),' ',COALESCE(last_name,'')) as full_name"));

            $users = $all_users->pluck('full_name', 'id');

            //Prepend none
            if ($prepend_none) {
                $users = $users->prepend('None', '');
            }

            return $users;
        });

        return $users;
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getUserFullNameAttribute()
    {
        return "{$this->surname} {$this->first_name} {$this->last_name}";
    }

    /**
     * Return true/false based on selected_contact access
     *
     * @return boolean
     */
    public static function isSelectedContacts($user_id)
    {
        $user = User::findOrFail($user_id);

        return (bool)$user->selected_contacts;
    }

    public function getRoleNameAttribute()
    {
        return explode('#', $this->getRoleNames()[0])[0];
    }
}
