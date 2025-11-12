<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $username
 * @property string $rank
 * @property int $hero_count
 * @property int $skin_count
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereHeroCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereSkinCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account whereUsername($value)
 */
	class Account extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $hero_name
 * @property string|null $url
 * @property string|null $hero_image
 * @property int $total_skins
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Skin> $skins
 * @property-read int|null $skins_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero whereHeroImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero whereHeroName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero whereTotalSkins($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Hero whereUrl($value)
 */
	class Hero extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $hero_id
 * @property string $skin_name
 * @property string|null $skin_image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Hero $hero
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin whereHeroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin whereSkinImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin whereSkinName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skin whereUpdatedAt($value)
 */
	class Skin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

