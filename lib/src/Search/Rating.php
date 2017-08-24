<?php
namespace GoFetchCode\Search;

/**
 * A search rating.
 */
class Rating
{
    private $id;

    private $user_id;
    
    private $search_string;
    
    private $created;
    
    private $rating;
    
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    
    public function __get($name)
    {
        return $this->$name;
    }
    
    /**
     * Find a rating by a search string.
     */
    public static function findBySearchString($userId, $searchString)
    {
        //TODO Too much code duplication with these queries.
        $db = getDB();
    
        $sql = 
            "SELECT * FROM search_ratings ".
            "WHERE user_id=:user_id ".
            "AND search_string=:search_string;";

        $stmt = $db->prepare($sql);
            
        $stmt->bindParam("user_id", $userId, \PDO::PARAM_INT);
        $stmt->bindParam("search_string", $searchString, \PDO::PARAM_STR);
        
        $stmt->execute();
        
        if ($object = $stmt->fetchObject("\GoFetchCode\Search\Rating")) {
            return $object;
        } else {
            return new \GoFetchCode\Search\Rating;
        }
    }
    
    /**
     * Bind an array to this object.
     *
     * A convenience method for binding arrays.
     *
     * @param  array  $data  The data to bind.
     */
    public function bind($data)
    {
        foreach ($data as $key=>$value) {
            $this->$key = $value;
        }
    }
    
    /**
     * Save the rating.
     *
     * @return  bool  True on success.
     */
    public function save()
    {
        //@TODO Need a common Date class to provide this functionality.
        $this->created = date("Y-m-d H:i:s");
    
        try {
            // @TODO This all needs to be abstracted out to a db layer.
            $db = getDB();
            
            $keys = [];
            $params = [];
            
            if ($this->id) {
                foreach (array_keys(get_object_vars($this)) as $key) {
                    if ($key !== "id") {
                        $keys[] = $key;
                        $params[] = $key."= :".$key;
                    }
                }
            
                $sql = 
                    "UPDATE search_ratings ".
                    "SET ".implode(",", $params)." ".
                    "WHERE id = :id";
            } else {
                foreach (array_keys(get_object_vars($this)) as $key) {
                    if ($key !== "id") {
                        $keys[] = $key;
                        $params[] = ":".$key;
                    }
                }
                
                $sql = 
                    "INSERT INTO search_ratings ".
                    "(".implode(",", $keys).") ".
                    "VALUES (".implode(",", $params).")";            
            }
            
            $stmt = $db->prepare($sql);
            
            // @TODO painful; we have to bind everything manually.
            if ($this->id) {
                $stmt->bindParam("id", $this->id, \PDO::PARAM_INT);
            }
            
            $stmt->bindParam("user_id", $this->user_id, \PDO::PARAM_INT);
            $stmt->bindParam("created", $this->created, \PDO::PARAM_STR);
            $stmt->bindParam("rating", $this->rating, \PDO::PARAM_INT);
            $stmt->bindParam("search_string", $this->search_string, \PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            // @TODO some kind of bizarre error handling.
            echo '{"error":{"text":'.$e->getMessage().'}}';
        }
    }
}
