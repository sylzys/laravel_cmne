<?php


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Laracasts');
    }

    public function loginPageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->see('Connexion');
    }

    public function signInSuccessfully(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('Email','a@a.com');
        $I->fillField('Mot de passe','a');
        $I->click('Connexion');
        $I->see('Tableau de bord');
    }
    public function signInNotSuccessful(AcceptanceTester $I)
    {
        $I->amOnPage('/admin/login');
        $I->fillField('Email','a@b.com');
        $I->fillField('Mot de passe','a');
        $I->click('Connexion');
        $I->see('These credentials do not match our records.');
    }
}
