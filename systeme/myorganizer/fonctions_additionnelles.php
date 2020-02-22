<?php

/**
 * Is user is admin
 * @return bool
 */
function is_admin(): bool
{
    return get_user_courant(MF_USER_ADMIN) === true;
}

/**
 * Is user is connected
 * @return bool
 */
function is_connected(): bool
{
    return get_user_courant(MF_USER__ID) !== null;
}