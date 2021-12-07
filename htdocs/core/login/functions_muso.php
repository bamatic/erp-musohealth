<?php
/* Copyright (C) 2010 Regis Houssin  <regis.houssin@inodbox.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *      \file       htdocs/core/login/functions_empty.php
 *      \ingroup    core
 *      \brief      Empty authentication functions for test
 */


/**
 * Check validity of user/password/entity
 * If test is ko, reason must be filled into $_SESSION["dol_loginmesg"]
 *
 * @param	string	$usertotest		Login
 * @param	string	$passwordtotest	Password
 * @param   int		$entitytotest   Number of instance (always 1 if module multicompany not enabled)
 * @return	string					Login if OK, '' if KO
 */
require_once DOL_DOCUMENT_ROOT.'/core/login/MusoGoogleClient.php';;
function check_user_password_muso($usertotest, $passwordtotest, $entitytotest)
{
    global $db, $conf, $langs;

    dol_syslog("functions_muso::check_user_password_muso usertotest=" . $usertotest);

    $login = '';
    MusoGoogleClient::init();
    $gClient = MusoGoogleClient::getGoogleClient();

    $gClient->authenticate($_GET['code']);
    $oAuth = new Google_Service_Oauth2($gClient);
    $userData = $oAuth->userinfo_v2_me->get();

    if ($usertotest == $_GET['code'])
        $usertotest = $userData->email;
    else {
        $_SESSION["dol_loginmesg"] = $langs->trans("ErrorBadLoginPassword");
        return $login;
    }

    $table = MAIN_DB_PREFIX . "user";
    $usernamecol1 = 'login';
    $usernamecol2 = 'email';
    $entitycol = 'entity';

    $sql = 'SELECT rowid, login';
    $sql .= ' FROM ' . $table;
    $sql .= ' WHERE (' . $usernamecol1 . " = '" . $db->escape($usertotest) . "'";
    if (preg_match('/@/', $usertotest)) $sql .= ' OR ' . $usernamecol2 . " = '" . $db->escape($usertotest) . "'";
    $sql .= ') ';
    $sql .= ' AND statut = 1';

    $resql = $db->query($sql);
    if ($resql) {
        $obj = $db->fetch_object($resql);
        if ($obj) {
            $login = $obj->login;
        } else {
            [$usr, $domain] = explode('@', $usertotest);

            if ($domain == 'musohealth.org') {

                $sql = 'INSERT INTO ' . $table;
                $sql .= " (datec,login,email,lastname,firstname,fk_country,google_url) VALUES ('";
                $sql .= date('Y-m-d') . "','" . $usertotest . "','" . $usertotest . "','" . $userData["given_name"] . "','" .
                    $userData["family_name"] . "',147,'" . $userData['picture'] . "')";

                $result = $db->query($sql);

                if ($result) {
                    $userId = $db->last_insert_id($table, 'rowid');
                    $sql = "INSERT INTO " . MAIN_DB_PREFIX . "usergroup_user";
                    $sql .= " (entity,fk_user,fk_usergroup) VALUES (1,";
                    $sql .= $userId . ",6)";

                    $db->query($sql);

                    return $usertotest;
                } else {
                    // Load translation files required by the page
                    $langs->loadLangs(array('main', 'errors'));

                    $_SESSION["dol_loginmesg"] = $langs->trans("ErrorBadLoginPassword");

                }

            } else {
                // Load translation files required by the page
                $langs->loadLangs(array('main', 'errors'));

                $_SESSION["dol_loginmesg"] = $langs->trans("ErrorBadLoginPassword");

            }

        }
    }

    return $login;
}
