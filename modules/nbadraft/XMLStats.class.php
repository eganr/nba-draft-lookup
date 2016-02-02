<?PHP

final class XMLStats {

    // Constants
    const ACCESS_TOKEN = 'fd249cc5-815e-41aa-ba24-10e5a11c4769';
    const USER_AGENT = 'nbadraftbot/0.1 (http://ryanegan.ca)';
    const TIME_ZONE = 'America/New_York';

    public function __construct() {
        // PHP complains if time zone is not set
        date_default_timezone_set('America/New_York');
    }

    public static function GetDraftDataForSeason($season)
    {
        // Set the API sport, method, id, format, and any parameters
        $host   = 'erikberg.com';
        $sport  = 'nba';
        $method = 'draft';
        $id     = '';
        $format = 'json';
        $parameters = array(
            'season' => $season
        );

        // Pass method, format, and parameters to build request url
        $url = self::buildURL($host, $sport, $method, $id, $format, $parameters);

        // Set the User Agent, Authorization header and allow gzip
        $default_opts = array(
            'http' => array(
                'user_agent' => self::USER_AGENT,
                'header'     => array(
                    'Accept-Encoding: gzip',
                    'Authorization: Bearer ' . self::ACCESS_TOKEN
                )
            )
        );
        stream_context_get_default($default_opts);
        $file = 'compress.zlib://' . $url;
        $fh   = fopen($file, 'rb');
        if ($fh && strpos($http_response_header[0], "200 OK") !== false) {
            $content = stream_get_contents($fh);
            fclose($fh);
            return json_decode($content);
        } else {
            return false;
        }
    }

    // See https://erikberg.com/api/methods Request URL Convention for
    // an explanation
    private static function buildURL($host, $sport, $method, $id, $format, $parameters)
    {
        $ary  = array($sport, $method, $id);
        $path = join('/', preg_grep('/^$/', $ary, PREG_GREP_INVERT));
        $url  = 'https://' . $host . '/' . $path . '.' . $format;

        // Check for parameters and create parameter string
        if (!empty($parameters)) {
            $paramlist = array();
            foreach ($parameters as $key => $value) {
                array_push($paramlist, rawurlencode($key) . '=' . rawurlencode($value));
            }
            $paramstring = join('&', $paramlist);
            if (!empty($paramlist)) { $url .= '?' . $paramstring; }
        }
        return $url;
    }

}
?>
