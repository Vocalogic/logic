<?php

namespace App\Enums\Core;

use Carbon\Carbon;

/**
 * This enumeration will control all of the session and cache key systems that are used for the
 * sbus and assistant components that are streaming communications between the admin
 * components and the client receivers/transmitters.
 *
 * -- [ UNDER NO CIRCUMSTANCES SHOULD SESSION/CACHE KEYS BE GENERATED AS STRINGS MANUALLY ] --
 */
enum CommKey: string
{
    /**
     * The cart data that is tracking locally from within the user session. All items, qty,
     * and pricing is pushed using ShopOperation and has sole authority over the user's session.
     */
    case LocalCartSession = 'cart';

    /**
     * If User is viewing a quote, we will store the quote information here for easy
     * retrieval and manipulation by either the user (if permitted) and the sales agent
     * and administrative controls.
     */
    case LocalQuoteSession = 'cart_quote';

    /**
     * When building a package from a wizard, we need to be able to save the answers
     * that a user gives so that when they ultimately create a quote or checkout
     * that we can retain any answers provided in multi-forms, etc.
     */
    case LocalPackageAnswerSession = 'cart_answers';

    /**
     * If the user selects a contract term and then navigates away we need to track
     * what the user selected, so when they go back into the checkout procedure
     * the proper term is auto-selected.
     */
    case LocalContractSession = 'guest_contract_term';

    /**
     * Session created with a unique id that is transmitted on the bus. This contains
     * activity timestamps, assistant commands and others. This is the localized version
     * of what is transmitted on the bus to keep the user in sync with everything they are
     * doing and what possibly the agent is sending over the bus.
     */
    case LocalCartCrossSession = 'cart_sess';

    /**
     * When executing an order or requesting a quote as a guest, we will need to perform
     * a 2FA method to verify the session. This key will hold that verification status. If
     * the user is verified, and moves off of the page or reverts back to a product, we will
     * hold the verification in the local session to prevent multiple authorization requirements.
     */
    case LocalVerificationSession = 'session_verified';

    /**
     * This key will contain any filters that have been applied by the user when browsing categories that
     * will persist as movement is made throught the browsing process.
     */
    case LocalFilterSession = 'shop_filters';

    /**
     * When signing with the signature pad, this session key will hold the data so that our
     * livewire components can poll it to make sure there is a valid signature.
     */
    case LocalSignatureData = 'signature_data';

    /**
     * This is one of the few admin sessions that are held for recently viewed leads, quotes, accounts, etc
     * for easy access during the search method.
     */
    case AdminSearchSession = 'admin_search_list';

    /**
     * When shadowing a customer account and we need to return to our administrative state,
     * this session key will be used to store the admin UID.
     */
    case AdminUidFromShadow = 'admin_old_uid';

    /**
     * This key is stored in the global cache and allows iteration from the administrative side
     * to see who is active currently on the system, their cart status and also will be used
     * to transmit any commands to the user from within the admin side.
     */
    case GlobalCartCache = 'cart_list';

    /**
     * Contains the current license data for Logic
     */
    case GlobalLicenseCache = 'logic_license';

    /**
     * Get the remote master version. This is used to inform customers to upgrade
     * if they want the latest features.
     */
    case GlobalLatestVersionCache = 'logic_latest';

    /**
     * When a user hits the site a session is generated, submitted to the bus,
     * and the IP will be attempted to be retrieved. Upon successful retrieval this keyval store
     * will be used to hold those for limited API calls.
     */
    case GlobalIPInventoryCache = 'logic_ip_list';

    /**
     * When a user clicks upgrade, we will trigger a backend procedure to check for the existence
     * of this key and trigger an upgrade. Once complete this key will be removed.
     */
    case GlobalUpgradeTrigger = 'logic_should_upgrade';

    /**
     * This will cache the MRR for an account for the invoice vs MRR graph
     * for each account.
     */
    case AccountMRRCache = 'account_mrr_cache';


    /**
     * All cache keys should have their lifetimes defined below. If no specific definition is defined
     * then a default of one hour expiry will be provided.
     * @return Carbon
     */
    public function getLifeTime(): Carbon
    {
        return match ($this)
        {
            self::GlobalCartCache => now()->addMinutes(30),
            self::GlobalLicenseCache => now()->addMinutes(5),
            self::GlobalIPInventoryCache => now()->addMonth(),
            self::AccountMRRCache => now()->addDay(),
            default => now()->addHour()
        };
    }
}
