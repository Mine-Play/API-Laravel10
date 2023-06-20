<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use PHPMinecraft\MinecraftQuery\MinecraftQueryResolver;
use PHPMinecraft\MinecraftQuery\Exception\MinecraftQueryException;

class Server extends Model
{
    use Notifiable, HasUuids;
    //use HasUuids;
    protected $table = 'servers';
    protected $connection = 'Global';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'params' => 'array'
    ];
    protected $attributes = [
        'query' => NULL
    ];
    protected $fillable = [
        'name',
        'slug',
        'address',
        'id',
        'description'
    ];
    protected $hidden = [
        'address'
    ];
    public static function getAll(){
        $servers = Server::select('id', 'uuid', 'name', 'slug', 'params', 'address')->get();
         for($i=0;$i<count($servers);$i++){
            $ping = Server::pingException($servers[$i]->makeVisible('address')->address);
            $servers[$i]->makeHidden('address');
            $servers[$i]->query = $ping;
         }
         return $servers;
    }
    public static function getByID($id){
        return Server::select('id', 'name', 'slug', 'params')->where('id', $id)->first();
    }
    public static function getBySlug($slug){
        return Server::select('id', 'name', 'slug', 'params')->where('slug', $slug)->first();
    }
    public static function pingException($address): array{
        $addr = explode(':', $address);
        $addr[1] = isset($addr[1]) ?? '25565'; 
        try {
            $resolver = new MinecraftQueryResolver(  $addr[0], $addr[1] );
            $result = $resolver->getResult();
            return [
                "status" => "online",
                "online" => $result->getOnlinePlayers(),
                "max" => $result->getMaxPlayers()
            ];
        }
        catch( MinecraftQueryException $e )
	    {
            return [
                "status" => "offline"
            ];
	    }   
    }
}
