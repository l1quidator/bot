<?php

class Database {
    private $database;

    public function __construct(){
        require_once ("config.php");
        date_default_timezone_set("Europe/Moscow");

    }

    public function AddUser($telegramId, $username = 'no_name'){
        $users = new Users();
        return $users->create(['telegram_id'=>$telegramId,'username'=>$username]);
    }

    public function GetUser($telegramId){
        return Users::find(['telegram_id'=>$telegramId]);
    }

    public function GetUserStatus($telegramId){

        return Users::find(['telegram_id'=>$telegramId])->status;
    }

    /**
     * @param $telegramId
     * @return bool|mixed
     * @throws \ActiveRecord\RecordNotFound
     */
    public function GetUserData($telegramId){

        return json_decode(Users::find(['telegram_id'=>$telegramId])->data, true);
    }

    /**
     * @param $telegramId
     * @return array
     * @throws \ActiveRecord\RecordNotFound
     */
    public function GetUserStats($telegramId){
        $user = $this->GetUser($telegramId);

        $payments = Payments::find('all', ['conditions' => ['telegram_id'=>$telegramId]]);
        if ($payments == null)
            return http_response_code(200);
        $links = Links::count(['telegram_id'=>$telegramId]);

        $topPlace = "Неизвестно";

        $topPlaces = Links::find_by_sql("SELECT payments.telegram_id, SUM(payments.amount) as amount, users.username FROM payments INNER JOIN users ON (users.telegram_id = payments.telegram_id) GROUP BY telegram_id ORDER BY amount DESC");

        foreach ($topPlaces as $i => $place)
            if ($place->username == $user->username)
                switch ($i + 1){
                    case 1: $topPlace = "#️⃣1️⃣"; break;
                    case 2: $topPlace = "#️⃣2️⃣"; break;
                    case 3: $topPlace = "#️⃣3️⃣"; break;
                    case 4: $topPlace = "#️⃣4️⃣"; break;
                    case 5: $topPlace = "#️⃣5️⃣"; break;
                    case 6: $topPlace = "#️⃣6️⃣"; break;
                    case 7: $topPlace = "#️⃣7️⃣"; break;
                    case 8: $topPlace = "#️⃣8️⃣"; break;
                    case 9: $topPlace = "#️⃣9️⃣"; break;
                    case 10: $topPlace = "#️⃣🔟"; break;
                    default: $topPlace = "#" . ($i + 1); break;
                }

        return [
            "username" => $user->username,
            "top_place" => $topPlace,
            "links_count" => $links,
            "payments_count" => count($payments),
            "total_amount" => Payments::summ($payments),

        ];
    }

    /**
     * @return string
     */
    public function GetTopUsers(){
        $users = "";

        $topPlaces = Links::find_by_sql("SELECT payments.telegram_id, SUM(payments.amount) as amount, users.nickname FROM payments INNER JOIN users ON (users.telegram_id = payments.telegram_id) GROUP BY telegram_id ORDER BY amount DESC LIMIT 10");

        foreach ($topPlaces as $i => $place){
            switch ($i + 1){
                case 1: $topPlace = "#️⃣1️⃣"; break;
                case 2: $topPlace = "#️⃣2️⃣"; break;
                case 3: $topPlace = "#️⃣3️⃣"; break;
                case 4: $topPlace = "#️⃣4️⃣"; break;
                case 5: $topPlace = "#️⃣5️⃣"; break;
                case 6: $topPlace = "#️⃣6️⃣"; break;
                case 7: $topPlace = "#️⃣7️⃣"; break;
                case 8: $topPlace = "#️⃣8️⃣"; break;
                case 9: $topPlace = "#️⃣9️⃣"; break;
                case 10: $topPlace = "#️⃣🔟"; break;
            }

            $users .= "<b>" . $topPlace . " #" . $place->nickname . " - " . number_format($place->amount, 0, "", " ") . " ₽</b>\r\n";
        }

        if ($users == "")
            $users = "На данный момент топ пустой";

        return $users;
    }

    /**
     * @param $telegramId
     * @param $status
     * @return mixed
     * @throws \ActiveRecord\RecordNotFound
     */
    public function SetUserStatus($telegramId, $status){

        $user = Users::find(['telegram_id'=>$telegramId]);
        $user->status = $status;
        return $user->save();
    }

    /**
     * @param $telegramId
     * @param $data
     * @return mixed
     * @throws \ActiveRecord\RecordNotFound
     */
    public function SetUserData($telegramId, $data){

        $user = Users::find(['telegram_id'=>$telegramId]);
        $user->data = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $user->save();

    }


    public function CreateLink($link){

        $links = new Links();
        $links->telegram_id = $link["telegram_id"];
        $links->order_id = $link["order_id"];
        $links->title = $link["title"];
        $links->price = $link["price"];
        $links->type = $link["type"];
        $links->payment_system = $link["payment_system"];
        $links->worker = $link["worker"];
        $links->data = json_encode($link["data"], JSON_UNESCAPED_UNICODE);


        return $links->save();
    }

    public function GetLinks($telegramId, $offset = 0,$type){

        $links =  $links = Links::find('all',['conditions' => ['telegram_id'=>$telegramId,'type'=>$type], 'limit' => 8,'offset'=>$offset * 8]);

        $pages = explode(".", (Links::count(['telegram_id'=>$telegramId,'type'=>$type]) / 8))[0];

        return [
            "links" => $links,
            "pages" => $pages + 1
        ];
    }

    public function GetLinkInfo($id){
        try {
            $links = Links::find($id);
            if($links != NULL){
                return $links;
            }else{
                return NULL;
            }
        }catch (Exception $e){
            return NULL;
        }

    }

    /**
     * @param $id
     * @param $title
     * @return bool|null
     * @throws \ActiveRecord\RecordNotFound
     */
    public function SetLinkTitle($id, $title){
        try {
            $links = Links::find($id);
            if ($links != NULL) {
                $links->title = $title;
                return $links->save();
            } else {
                return NULL;
            }
        }catch (Exception $e){
            return NULL;
        }


    }

    public function SetLinkPrice($id, $price){
        $links = Links::find($id);
        $links->price = $price;

        return $links->save();
    }

    public function RemoveLink($id){

        return Links::find($id)->delete();
    }
}

?>