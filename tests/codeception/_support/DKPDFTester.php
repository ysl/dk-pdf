<?php
// phpcs:disable

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class DKPDFTester extends \Codeception\Actor
{
    use \_generated\AcceptanceTesterActions;

    /* --------------------------------------------------------------
     * MultilingualPress Utility
     * ----------------------------------------------------------- */

    /**
     * Disable Onboarding
     */
    public function dismissOnboarding(int $siteId = 1)
    {
        $I = $this;

        $pointers = [
            'multilingualpress_edit_relationships_languages',
            'multilingualpress_edit_site_language',
            'multilingualpress_edit_site',
            'multilingualpress_new_relationships_languages',
            'multilingualpress_based_on_site',
            'multilingualpress_add_site',
            'multilingualpress_add_site',
            'multilingualpress_settings_dynamic_permalinks',
            'settings_dynamic_permalinks',
        ];

        $I->haveInDatabase('wp_sitemeta', [
            'meta_key' => 'onboarding_dismissed',
            'meta_value' => 1,
            'site_id' => $siteId,
        ]);
        $I->haveInDatabase('wp_usermeta', [
            'meta_key' => '_dismissed_mlp_pointers',
            'meta_value' => implode(',', $pointers),
            'user_id' => 1,
        ]);
    }

    /**
     * Activate Multilingualpress Module.
     * @param string $moduleSlug
     */
    public function activateModule(string $moduleSlug)
    {
        $I = $this;

        $I->amOnPage('/wp-admin/network/admin.php?page=multilingualpress');
        $I->checkOption("#multilingualpress-module-{$moduleSlug}");
        $I->click('#submit');
    }


    /* --------------------------------------------------------------
     * Users Utility
     * ----------------------------------------------------------- */

    /**
     * Login as admin
     */
    public function loginAsAdmin()
    {
        $I = $this;
        $I->amOnPage('/wp-login.php');
        $I->wait(1);
        $I->fillField(['name' => 'log'], 'admin');
        $I->fillField(['name' => 'pwd'], 'password');
        $I->click('#wp-submit');
    }

    /**
     * Logout
     */
    public function logout()
    {
        $I = $this;
        $I->amOnPage('/wp-login.php?action=logout');
        $I->click('log out');
    }


    /* --------------------------------------------------------------
     * Network Site Utility
     * ----------------------------------------------------------- */

    /**
     * Connect two sites
     */
    public function connectTwoSites()
    {
        $I = $this;

        // edit Site 1 MultilingualPress tab
        $I->amOnPage('/wp-admin/network/sites.php?page=multilingualpress-site-settings&id=1');

        // see if English is selected
        $language = $I->grabValueFrom('#mlp-site-language-tag');
        $I->assertEquals('en-US', $language);

        // save changes
        $I->click('Save Changes');

        // edit Site 2 MultilingualPress tab
        $I->amOnPage('/wp-admin/network/sites.php?page=multilingualpress-site-settings&id=2');

        // select German language
        $I->executeJS("jQuery('#mlp-site-language-tag').val('de-DE')");

        // check Relationship checkbox (WordPress - en_US)
        $I->checkOption('#mlp-site-relations-1');

        // save changes
        $I->click('Save Changes');
    }

    /**
     * Add new site
     */
    public function addNewSite()
    {
        $I = $this;

        // go to add new site
        $I->amOnPage('/wp-admin/network/site-new.php');

        // reload the page to get rid of tooltip (WordPress Internal Pointer)
        $I->wait(2);
        $I->reloadPage();

        // Fill WordPress fields
        $I->fillField('#site-address', 'site3');
        $I->fillField('#site-title', 'Site 3');
        $I->selectOption('#site-language', 'Español');
        $I->fillField('#admin-email', 'site3@example.com');

        // see MultilingualPress fields
        $I->seeOptionIsSelected('#mlp-site-language', 'Español (España)');
        $I->dontSeeCheckboxIsChecked('#mlp-site-relations-1');
        $I->dontSeeCheckboxIsChecked('#mlp-site-relations-2');
        $I->seeOptionIsSelected('#mlp-base-site-id', 'Choose site');
        $I->seeCheckboxIsChecked('#mlp-search-engine-visibility');

        // add site
        $I->click('Add Site');
        $I->see('Site added.');

        // go to site 3 multilingualpress tab
        $I->amOnPage('/wp-admin/network/sites.php?page=multilingualpress-site-settings&id=3');

        // see if fields values are correct
        $language = $I->grabValueFrom('#mlp-site-language-tag');
        $I->assertEquals('es-ES', $language);
        $I->dontSeeCheckboxIsChecked('#mlp-site-relations-1');
        $I->dontSeeCheckboxIsChecked('#mlp-site-relations-2');
    }

    /**
     * Create Site
     *
     * @param string $address
     * @param string $title
     * @param string $language
     * @param string $email
     *
     * @return void
     */
    public function createSite(string $address, string $title, string $language, string $email)
    {
        $I = $this;

        // go to add new site
        $I->amOnPage('/wp-admin/network/site-new.php');

        // reload the page to get rid of tooltip (WordPress Internal Pointer)
        $I->wait(2);
        $I->reloadPage();

        // Fill WordPress fields
        $I->fillField('#site-address', $address);
        $I->fillField('#site-title', $title);
        $I->selectOption('#site-language', $language);
        $I->fillField('#admin-email', $email);

        // add site
        $I->click('Add Site');
        $I->see('Site added.');
    }

    /**
     * Connect sites.
     *
     * @param int $toConnect Site id where connect sites.
     * @param int[] $ids Site ids to connect.
     *
     * @return void
     */
    public function connectSites(int $toConnect, array $ids)
    {
        $I = $this;

        $I->amOnPage("/wp-admin/network/sites.php?page=multilingualpress-site-settings&id={$toConnect}");
        foreach ($ids as $site_id) {
            $I->checkOption("#mlp-site-relations-{$site_id}");
        }
        $I->click('Save Changes');
        $I->see('Settings saved.');
    }

    /**
     * Enable Post Type for translation.
     *
     * @param string $postType
     */
    public function enablePostTypeForTranslation(string $postType)
    {
        $I = $this;

        $I->amOnPage('/wp-admin/network/settings.php?page=multilingualpress&tab=post-types');
        $I->checkOption("#mlp-post-type-{$postType}");
        $I->click('#submit');
        $I->see('Settings saved.');
    }

    /**
     * Enable Taxonomy Term for translation
     *
     * @param string $taxonomy
     */
    public function enableTaxonomyTermForTranslation(string $taxonomy)
    {
        $I = $this;

        $I->amOnPage('/wp-admin/network/settings.php?page=multilingualpress&tab=taxonomies');
        $I->checkOption("#mlp-taxonomy-{$taxonomy}");
        $I->click('#submit');
        $I->see('Settings saved.');
    }


    /* --------------------------------------------------------------
     * Posts Utility
     * ----------------------------------------------------------- */

    /**
     * Connect two posts
     */
    public function connectTwoPosts()
    {
        $i = $this;

        $i->amOnPage('/wp-admin/post.php?post=1&action=edit');

        $i->executeJS('jQuery( "#multilingualpress-site-2-relationship-existing" ).trigger( "click" );');
        $i->fillField('#multilingualpress-site-2-search_post_id', 'Hello');

        $i->waitForElement('.search-results-row');
        $i->click('.search-results-row td label');

        $i->wait(1);
        $i->click('Update now');
    }

    /**
     * Create a post in specific site.
     *
     * @param string $queryParameter
     * @param string $site
     * @param array $args
     * @return int $postId
     */
    public function createPost(string $queryParameter = '', string $site = '', array $args = [])
    {
        $I = $this;

        $args = (object)array_merge([
            'title' => 'Post Title',
            'content' => 'Post content.',
        ], $args);

        $url = '/wp-admin/post-new.php';
        $site = $this->ensureSite($site);
        $url = $site . $url;
        $url = $queryParameter ? "{$url}?{$queryParameter}" : $url;

        // go to site 1 posts / new
        $I->amOnPage($url);
        $postId = (int)$I->grabAttributeFrom('#post_ID', 'value');

        $I->fillField('#title', $args->title);

        $I->click('#content-html');
        $I->fillField('#content', $args->content);
        $I->wait(2);

        // publish post
        $I->publishCurrentPost();
        $I->see('published.');

        return $postId;
    }

    /**
     * Create a post in site 1 with featured image.
     */
    public function createPostWithFeaturedImage()
    {
        $I = $this;

        // go to site 1 posts / new
        $I->amOnPage('/wp-admin/post-new.php');

        $I->fillField('#title', 'English Post');

        $I->click('#content-html');
        $I->fillField('#content', 'English Post content.');
        $I->wait(2);

        // add featured image
        $I->click('#set-post-thumbnail');
        $I->click('Media Library');
        $I->wait(2);
        $I->click('.thumbnail');
        $I->wait(1);
        $I->executeJS('jQuery( ".media-button-select" ).trigger( "click" );');
        $I->wait(1);

        // publish post
        $I->executeJS('jQuery( "#publish" ).trigger( "click" );');
        $I->wait(1);
        $I->see('Post published.');
    }

    /**
     * Connect 2 existing posts.
     *
     * @param string $siteSource
     * @param string $siteTarget
     * @param int $postId
     * @param string $targetPostName
     * @param string $postType
     */
    public function connectExistingPosts(
        string $siteSource,
        string $siteTarget,
        int $postId,
        string $targetPostName,
        string $postType
    ) {
        $I = $this;

        $site = '' === $siteSource ? '' : "/{$siteSource}";
        $siteTarget = strtolower(str_replace(' ', '-', $siteTarget));

        $url = "{$site}/wp-admin/post.php?post={$postId}&action=edit";
        if ('post' !== $postType) {
            $url .= "&post_type={$postType}";
        }

        $I->amOnPage($url);

        $I->checkOption("#multilingualpress-{$siteTarget}-relationship-existing");

        $I->fillField("#multilingualpress-{$siteTarget}-search_post_id", $targetPostName);
        $I->wait(1);

        $I->executeJS('jQuery(".search-results-row").find("input").trigger("click");');
        $I->wait(1);

        $I->click('.tab-relation .update-relationship');
        $I->wait(1);
    }

    /**
     * Create new relation for a post, the target post will be created automatically.
     *
     * @param string $siteSlug
     */
    public function createNewGutenbergPostRelation(string $siteSlug)
    {
        $I = $this;

        $selector = "#multilingualpress-{$siteSlug}-tab-relation";

        $I->click("a[href=\"{$selector}\"]");
        $I->wait(1);
        $I->checkOption("#multilingualpress-{$siteSlug}-relationship-new");
        $I->click('.editor-post-publish-panel__toggle');
        $I->wait(1);
        $I->click('.editor-post-publish-button');
        $I->waitForText('Currently connected with');
    }

    /**
     * Assign terms to post.
     *
     * @param string $site
     * @param int $postId
     * @param array $taxonomyTerms
     */
    public function assignTaxonomyTermsToPost(string $site, int $postId, array $taxonomyTerms)
    {
        $I = $this;

        $I->amOnPage("/{$site}/wp-admin/post.php?post={$postId}&action=edit");

        foreach ($taxonomyTerms as $term) {
            $I->executeJS(
                "jQuery('label:contains({$term})').find('input').attr('checked', 'checked')"
            );
        }

        $I->publishCurrentPost();
    }

    /**
     * Publish current viewing post in backend.
     */
    public function publishCurrentPost()
    {
        $I = $this;

        $I->executeJS('jQuery( "#publish" ).trigger( "click" );');
        $I->wait(1);
    }


    /* --------------------------------------------------------------
     * Menu Utility
     * ----------------------------------------------------------- */

    /**
     * Create menu on site 1.
     */
    public function createMenu()
    {
        $I = $this;

        // go to site 1 menus
        $I->amOnPage('/wp-admin/nav-menus.php?action=edit&menu=0');

        // create menu
        $I->fillField('#menu-name', 'Primary Menu');
        $I->click('Create Menu');
        $I->click('#locations-top');
        $I->click('Save Menu');
        $I->see('Primary Menu has been updated.');
    }

    /**
     * Add Languages Menu To Menu Sidebar
     */
    public function addLanguagesMenuItemsToMenuSidebar()
    {
        $I = $this;

        // check languages in screen options
        $I->click('#show-settings-link');
        $I->wait(1);
        $I->checkOption('#mlp-languages-hide');
    }

    /**
     * Add Languages Menu Items into the menu
     */
    public function addLanguagesMenuItemsToMenu()
    {
        $I = $this;

        // display languages accordion
        $I->click('#mlp-languages');
        $I->executeJS('window.scrollTo(0,700);');
        $I->wait(1);

        // add language items to menu
        $I->click('#mlp-languages-select-all');
        $I->wait(1);
        $I->click('#mlp-languages-submit');
        $I->wait(1);

        // save menu
        $I->click('Save Menu');
    }


    /* --------------------------------------------------------------
     * Terms/Taxonomy Utility
     * ----------------------------------------------------------- */

    /**
     * Create Translation For Term.
     *
     * @param string $queryParameters
     * @param string $termLink
     * @param string $site
     * @param string $siteSlug
     * @param array $args
     */
    public function createTranslationForTerm(
        string $queryParameters,
        string $termLink,
        string $site,
        string $siteSlug,
        array $args
    ) {

        $I = $this;

        $site = $this->ensureSite($site);

        $I->amOnPage("{$site}/wp-admin/edit-tags.php?{$queryParameters}");

        $I->click($termLink);

        $I->checkOption("#multilingualpress-{$siteSlug}-relationship-new");
        $I->wait(1);

        $I->click('Term Data');
        $I->fillField("#multilingualpress-{$siteSlug}-remote-name", $args['title']);
        $I->fillField("#multilingualpress-{$siteSlug}-remote-slug", $args['slug']);
        $I->click('Update');
    }

    /**
     * Create taxonomy terms.
     *
     * @param string $site
     * @param array $list
     */
    public function createTaxonomyTerms(string $queryParameter, string $site, array $list = [])
    {
        $I = $this;

        $I->amOnPage("/{$site}/wp-admin/{$queryParameter}");

        foreach ($list as $item) {
            $I->fillField('#tag-name', $item['title'] ?? 'Term Title');
            isset($item['slug']) and $I->fillField('#tag-description', $item['slug']);
            isset($item['description']) and $I->fillField('#tag-description', $item['description']);
            $I->click('#submit');
            $I->wait(1);
        }
    }


    /* --------------------------------------------------------------
     * Plugins Utility
     * ----------------------------------------------------------- */

    /**
     * Install Plugin.
     *
     * Try to install the plugin if the card is already in the page.
     */
    public function installAndActivatePluginNetwork(string $slug)
    {
        $I = $this;

        $I->amOnPage('/wp-admin/network/plugin-install.php');

        try {
            $I->scrollTo(".plugin-card-{$slug}");
            $I->click(".plugin-card-{$slug} .install-now");
        } catch (\Throwable $thr) {
            $I->scrollTo('html');
            $I->fillField('.wp-filter-search', $slug);
            $I->waitForElement(".plugin-card-{$slug}");

            $I->seeElement(".plugin-card-{$slug} .install-now");
            $I->click(".plugin-card-{$slug} .install-now");
        }

        $I->waitForElementChange(".plugin-card-{$slug} .install-now", function ($el) {
            return false !== strpos($el->getAttribute('class'), 'activate-now');
        }, 100);
        $I->click(".plugin-card-{$slug} .activate-now");
        $I->see('Plugin activated.');
    }

    /**
     * Activate Plugin Network.
     *
     * @param string $slug
     */
    public function activatePluginNetwork(string $slug)
    {
        $I = $this;

        $I->amOnPage('/wp-admin/network/plugins.php');

        $I->seeElement("[data-slug=\"{$slug}\"]");

        $I->click("[data-slug=\"{$slug}\"] .activate a");
        $I->see('Plugin activated.');
    }

    /**
     * Deactivate Plugin Network.
     *
     * @param string $slug
     */
    public function deactivatePluginNetwork(string $slug)
    {
        $I = $this;

        $I->amOnPage('/wp-admin/network/plugins.php');

        try {
            $I->seeElement("[data-slug=\"{$slug}\"]");
            $I->click("[data-slug=\"{$slug}\"] .deactivate a");
        } catch (\Throwable $thr) {
            return;
        }

        $I->wait(2);
        $I->see('Plugin deactivated.');
    }

    /**
     * Delete Plugin.
     *
     * @param string $slug
     */
    public function uninstallAndDeletePlugin(string $slug)
    {
        $I = $this;

        $I->amOnPage('/wp-admin/network/plugins.php');

        try {
            $I->seeElement("[data-slug=\"{$slug}\"]");
            $I->click("[data-slug=\"{$slug}\"] .delete a");
            $I->acceptPopup();
        } catch (\Throwable $thr) {
            return;
        }

        $I->wait(3);
        $I->see('was successfully deleted.');
    }


    /* --------------------------------------------------------------
     * Gutenberg Utility
     * ----------------------------------------------------------- */

    /**
     * Setup Gutenberg
     */
    public function setupGutenberg()
    {
        $I = $this;

        $I->uninstallGutenbergPlugin();
        $I->installAndActivatePluginNetwork('gutenberg');
    }

    /**
     * Uninstall Gutenberg
     */
    public function uninstallGutenbergPlugin()
    {
        $I = $this;

        $I->deactivatePluginNetwork('gutenberg');
        $I->uninstallAndDeletePlugin('gutenberg');
    }


    /* --------------------------------------------------------------
     * WooCommerce Utility
     * ----------------------------------------------------------- */

    /**
     * Discard annoying WooCommerce Wizard page.
     *
     * @param string $site
     */
    public function discardWooCommerceWizard(string $site = '')
    {
        $I = $this;

        $site = $this->ensureSite($site);

        $I->amOnPage($site . '/wp-admin/index.php?page=wc-setup');
        $I->click('Not right now');
    }

    /**
     * Create product into a specific site.
     *
     * @param string $sitePage
     * @param string $title
     */
    public function createProductOnSite(string $sitePage, string $title)
    {
        $I = $this;

        $I->amOnPage($sitePage);

        $I->fillField('#title', $title);
        $I->fillField('#_regular_price', '1');
        $I->publishCurrentPost();
    }

    /**
     * Set WooCommerce Shop page option.
     *
     * @param string $pageName
     * @param string $site
     */
    public function setWooCommerceShopPageSetting(string $pageName, string $site = '')
    {
        $I = $this;

        $I->amOnPage("{$site}/wp-admin/admin.php?page=wc-settings&tab=products");
        $I->selectOption('#woocommerce_shop_page_id', $pageName);
        $I->click('.woocommerce-save-button');
    }

    /**
     * Install WooCommerce Plugin
     */
    public function setupWooCommercePlugin()
    {
        $I = $this;

        $I->uninstallWooCommercePlugin();
        $I->installAndActivatePluginNetwork('woocommerce');

        $I->enablePostTypeForTranslation('product');

        $I->discardWooCommerceWizard();
        $I->discardWooCommerceWizard('site2');
    }

    /**
     * Uninstall WooCommerce Plugin
     */
    public function uninstallWooCommercePlugin()
    {
        $I = $this;

        $I->deactivatePluginNetwork('woocommerce');
        $I->uninstallAndDeletePlugin('woocommerce');
    }


    /* --------------------------------------------------------------
     * Permalinks Utility
     * ----------------------------------------------------------- */

    /**
     * Update permalinks for specific site.
     *
     * @param string $site
     */
    public function updateSitePermalink(string $site = '')
    {
        $I = $this;

        $site = $this->ensureSite($site);

        $I->amOnPage($site . '/wp-admin/options-permalink.php');
        $I->click('#submit');
    }

    /**
     * Set Permalink Structure for site.
     *
     * @param string $site
     * @param string $structure
     */
    public function setPermalinkStructureForSite(string $site, string $structure)
    {
        $I = $this;

        $site = $this->ensureSite($site);

        $I->amOnPage("{$site}/wp-admin/options-permalink.php");
        $I->checkOption('#custom_selection');
        $I->fillField('#permalink_structure', $structure);
        $I->click('#submit');
    }

    /**
     * Set Category Base for site.
     *
     * @param string $site
     * @param string $base
     */
    public function setCategoryBaseForSite(string $site, string $base)
    {
        $I = $this;

        $site = $this->ensureSite($site);

        $I->amOnPage("{$site}/wp-admin/options-permalink.php");
        $I->fillField('#category_base', $base);
        $I->click('#submit');
    }

    /**
     * Set Product Category Base for site.
     *
     * @param string $site
     * @param string $base
     */
    public function setProductCategoryBaseForSite(string $site, string $base)
    {
        $I = $this;

        $site = $this->ensureSite($site);

        $I->amOnPage("{$site}/wp-admin/options-permalink.php");
        $I->fillField('[name="woocommerce_product_category_slug"]', $base);
        $I->click('#submit');
    }


    /* --------------------------------------------------------------
     * Private Utility
     * ----------------------------------------------------------- */

    /**
     * Ensure site name.
     *
     * @param string $site
     * @return string
     */
    private function ensureSite(string $site)
    {
        return $site ? '/' . trim($site, '/\\') : '';
    }
}
