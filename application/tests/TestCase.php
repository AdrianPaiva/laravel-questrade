<?php

namespace Tests;

use App\Models\Company;
use App\Models\Domain;
use App\Models\DomainSetting;
use App\Models\DomainTheme;
use App\Models\Industry;
use App\Models\Plan;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;
    
    /**
     * @var \App\Models\Domain|null
     */
    protected $domain = null;
    
    /**
     * @var \App\Models\Profile|null
     */
    protected $profile = null;
    
    /**
     * @var \App\Models\User|null
     */
    protected $user = null;
    
    protected function setUp()
    {
        parent::setUp();
        
        # If need to run these queries on every test, then uncomment it.
        // $this->artisan('passport:install');
        // $this->artisan('db:seed', ['--class' => 'IndustrySeeder']);
        // $this->artisan('db:seed', ['--class' => 'PlanSeeder']);
        // $this->artisan('db:seed', ['--class' => 'CountrySeeder']);
        // $this->artisan('db:seed', ['--class' => 'DeviceTypeSeeder']);
        // $this->artisan('db:seed', ['--class' => 'ComponentTypeSeeder']);
        // $this->artisan('db:seed', ['--class' => 'PermissionTypeSeeder']);
        // $this->artisan('db:seed', ['--class' => 'ComponentSeeder']);
        // $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);
    }
    
    /**
     * @param array $attributes
     * @param \App\Models\Plan|null $plan
     * @param \App\Models\Industry|null $industry
     *
     * @return \App\Models\Domain
     */
    protected function createDomain($attributes = [], Plan $plan = null, Industry $industry = null)
    {
        $plan = $plan ?? Plan::first();
        $industry = $industry ?? Industry::first();
        
        if (!key_exists('company_id', $attributes)) {
            
            $company = $this->createCompany($plan, $industry);
            
            $attributes['company_id'] = $company->id;
        }
        
        $attributes['plan_id'] = $plan->id;
        
        /** @var \App\Models\Domain $domain */
        $domain = factory(Domain::class)->create($attributes);
        $domain->industries()->attach($industry);
        $domain->createRoles();
        
        $domain->domainSetting()->save(factory(DomainSetting::class)->make());
        
        $this->createDomainTheme($domain, [
            'is_applied'       => true,
            'is_default_theme' => true,
        ]);
        
        return $domain;
    }
    
    /**
     * @param \App\Models\Plan $plan
     * @param \App\Models\Industry $industry
     *
     * @return \App\Models\Company
     */
    private function createCompany(Plan $plan, Industry $industry)
    {
        /** @var \App\Models\Company $company */
        $company = factory(Company::class)->create([
            'plan_id' => $plan->id,
        ]);
        
        $company->industries()->attach($industry);
        
        return $company;
    }
    
    /**
     * @param \App\Models\Domain $domain
     * @param array $attributes
     *
     * @return \App\Models\DomainTheme
     */
    protected function createDomainTheme(Domain $domain, $attributes = [])
    {
        return $domain->domainThemes()->save(factory(DomainTheme::class)->make($attributes));
    }
    
    /**
     * @param array $attributes
     *
     * @return \App\Models\User
     */
    protected function createUser($attributes = [])
    {
        return factory(User::class)->create($attributes);
    }
    
    /**
     * @param \App\Models\Domain $domain
     * @param \App\Models\Role $role
     * @param array $attributes
     * @param \App\Models\User $user
     *
     * @return \App\Models\Profile
     */
    protected function createProfile(Domain $domain, Role $role, $attributes = [], User $user = null)
    {
        if (is_null($user)) {
            $user = $this->createUser($attributes);
        }
        
        $attributes['domain_id'] = $domain->id;
        $attributes['role_id'] = $role->id;
        
        return $user->profile()->save(factory(Profile::class)->make($attributes));
    }
    
    protected function createSampleData()
    {
        $this->domain = $this->createDomain([
            'name'       => 'test',
            'sub_domain' => 'test',
        ]);
        
        $role = $this->domain->roles()->whereName(Role::USER)->first();
        
        $this->profile = $this->createProfile($this->domain, $role);
        
        $this->user = $this->profile->user;
    }
}
