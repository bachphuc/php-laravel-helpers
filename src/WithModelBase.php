<?php
namespace bachphuc\PhpLaravelHelpers;

trait WithModelBase
{
    protected $itemType = '';
    protected static $itemsWith = [];

    public function hasField($field)
    {
        if (!$field) {
            return false;
        }

        $coreFields = ['id', 'created_at', 'updated_at'];
        if (in_array($field, $coreFields)) {
            return true;
        }

        return in_array($field, $this->fillable);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->itemType;
    }

    public function getTitle()
    {
        if ($this->hasField('title')) {
            return $this->title;
        }
        if ($this->hasField('name')) {
            return $this->name;
        }
        return '';
    }

    public function getDesc()
    {
        if ($this->hasField('description')) {
            return $this->description;
        }
        return '';
    }

    public function getHref()
    {
        return url(str_plural($this->itemType) . '/' . $this->id);
    }

    public function getAdminHref(){
        return url('admin/' . str_plural($this->itemType) . '/' . $this->id);
    }

    public function getImage()
    {
        if ($this->hasField('image')) {
            if (!empty($this->image)) {
                return url($this->image);
            }
        }
        if ($this->hasField('picture')) {
            if (!empty($this->picture)) {
                return url($this->picture);
            }
        }
        if ($this->hasField('photo')) {
            if (!empty($this->photo)) {
                return url($this->photo);
            }
        }
        return null;
    }

    public function remove()
    {
        return $this->delete();
    }

    /**
     * Increase field to one or total.
     */
    public function increase($field, $total = 1)
    {
        if (empty($field)) {
            return false;
        }

        if (in_array($field, $this->fillable)) {
            return $this->increment($field, $total);
        } else {
            $field = 'total_' . $field;
            if (in_array($field, $this->fillable)) {
                return $this->increment($field, $total);
            }
        }
        return false;
    }

    /**
     * Displays model when return to API.
     */
    public function display()
    {
        $result = $this->toArray();
        return $result;
    }

    /**
     * Display model item detail when return to API get model detail.
     */
    public function displayDetail()
    {
        return $this->display();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($item) {
            $item->onCreated();
        });
    }

    /**
     * Event when model has been created.
     */
    public function onCreated()
    {

    }

    public static function getItemsWith($params = [])
    {
        return static::$itemsWith;
    }

    public static function getListItems(\App\User $user = null, $params = array(), $bReturnQuery = false)
    {
        $modelClass = get_called_class();

        // list mode: timeline|page
        $listMode = 'timeline';
        $orderBy = isset($params['order_by']) && !empty($params['order_by']) ? $params['order_by'] : 'created_at';

        if (isset($params['page']) || isset($params['start'])) {
            $listMode = 'page';
        }

        $length = isset($params['length']) ? (int) $params['length'] : 10;

        $query = $modelClass::with($modelClass::getItemsWith($params));

        if ($user) {
            $query->where('user_id', $user->id);
        } else if (isset($params['user_id']) && !empty($params['user_id'])) {
            $query->where('user_id', $params['user_id']);
        }

        if ($listMode == 'timeline') {
            // handle list mode is timeline to support load new load more
            $type = isset($params['type']) && !empty($params['type']) ? $params['type'] : 'more';
            $maxId = isset($params['max_id']) ? $params['max_id'] : 0;
            $minId = isset($params['min_id']) ? $params['min_id'] : 0;

            if ($type == 'more' && !empty($maxId)) {
                $query->where($orderBy, '<', $maxId);
            }

            if ($type == 'new') {
                if (empty($minId)) {
                    return [];
                } else {
                    $query->where($orderBy, '>', $minId);
                }
            }
        } else {
            // handle list mode is page
        }

        $modelClass::processGetItemListQuery($query, $params);
        if ($bReturnQuery) {
            return $query;
        }

        if ($listMode == 'timeline') {
            if ($type == 'more') {
                $query->orderBy($orderBy, 'DESC');
            } else {
                $query->orderBy($orderBy, 'ASC');
            }
        } else {
            $query->orderBy($orderBy, 'DESC');
            if (isset($params['page'])) {
                $page = (int) $params['page'];
                $query->skip($page * $length);
            } else if (isset($params['start'])) {
                $start = (int) $params['start'];
                $query->skip($start);
            }
        }

        $query->take($length);

        $items = $query->get();
        return $items;
    }

    public static function processGetItemListQuery(&$query, &$params = [])
    {

    }

    public static function displays($items)
    {
        $results = [];
        foreach ($items as $item) {
            $results[] = $item->display();
        }

        return $results;
    }

    public function getField($field, $params = []){
        if($this->hasField($field)) return $this->{$field};

        return null;
    }
}
