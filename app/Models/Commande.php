<?php

namespace App\Models;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class Commande extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'due_date',
        'user_id',
        'client_id',
        'total_amount',
        'status',
    ];

    public function registerMediaCollections(): void
    {
        $media_storage_settings = get_settings('media_storage_settings');
        $mediaStorageType = $media_storage_settings['media_storage_type'] ?? 'local';
        if ($mediaStorageType === 's3') {
            $this->addMediaCollection('commande-media')->useDisk('s3');
        } else {
            $this->addMediaCollection('commande-media')->useDisk('public');
        }
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'commande_products');
                    //->withPivot('quantity', 'price')
                    //->withTimestamps();
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function getresult()
    {
        return substr($this->title, 0, 100);
    }

    public function getlink()
    {
        return str('/commandes/information/' . $this->id);
        return str('/commandes/draggable/' . $this->id);
    }

}
