<?php

declare(strict_types=1);

/** Generell funktions */
require_once __DIR__ . '/../libs/_traits.php';

/** Namespaced traits */
use Wilkware\LocalTuya\DebugHelper;
use Wilkware\LocalTuya\VariableHelper;

/**
 * CLASS VacuumCleaner
 */
class VacuumCleaner extends IPSModuleStrict
{
    // -------------------------------------------------------------------------
    // Traits
    // -------------------------------------------------------------------------

    use DebugHelper;
    use VariableHelper;

    // -------------------------------------------------------------------------
    // Constants
    // -------------------------------------------------------------------------

    /** @var string Splitter Modul IDs */
    private const GUID_MQTT_IO = '{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}';

    /** @var string MQTT TX Module ID (from module to server)*/
    private const GUID_MQTT_TX = '{043EA491-0325-4ADD-8FC2-A30C8EEB4D3F}';

    /** @var string MQTT RX Module ID (from server to module) */
    //private const GUID_MQTT_RX = '{7F7632D9-FA40-4F38-8DEA-C83CD4325A32}';

    // -------------------------------------------------------------------------
    // Presentations
    // -------------------------------------------------------------------------

    /**
     * @var array<string,mixed> Presentation (Switch)
     */
    private const T2MVC_PRESENTATION_SWITCH = [
        'GLOW_COLOR'     => 16771899,
        'GLOW_INTENSITY' => 50,
        'ICON_FALSE'     => 'power-off',
        'ICON_TRUE'      => 'power-off',
        'PRESENTATION'   => VARIABLE_PRESENTATION_SWITCH,
        'USAGE_TYPE'     => 0,
        'USE_ICON_FALSE' => false,
    ];

    /**
     * @var array<string,mixed> ModePresentation (Enumeration)
     */
    private const T2MVC_PRESENTATION_MODE = [
        'DISPLAY'      => 0,
        'ICON'         => 'vacuum-robot',
        'LAYOUT'       => 0,
        'OPTIONS'      => '[{"Value":"standby","Caption":"Standby","IconActive":false,"IconValue":"","Color":-1},{"Value":"smart","Caption":"Smart","IconActive":false,"IconValue":"","Color":-1},{"Value":"wall_follow","Caption":"Edges","IconActive":false,"IconValue":"","Color":-1},{"Value":"spiral","Caption":"Spiral","IconActive":false,"IconValue":"","Color":-1},{"Value":"partial_bow","Caption":"Zigzag","IconActive":false,"IconValue":"","Color":-1},{"Value":"chargego","Caption":"Charge","IconActive":false,"IconValue":"","Color":-1}]',
        'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
    ];

    /**
     * @var array<string,mixed> Direction Presentation (Enumeration)
     */
    private const T2MVC_PRESENTATION_DIRECTION = [
        'DISPLAY'      => 0,
        'ICON'         => 'compass',
        'LAYOUT'       => 0,
        'OPTIONS'      => '[{"Value":"forward","Caption":"Forward","IconActive":true,"IconValue":"right","Color":-1},{"Value":"turn_left","Caption":"Turn left","IconActive":true,"IconValue":"turn-left","Color":-1},{"Value":"turn_right","Caption":"Turn right","IconActive":true,"IconValue":"turn-right","Color":-1},{"Value":"stop","Caption":"Stop","IconActive":true,"IconValue":"stop","Color":-1},{"Value":"exit","Caption":"Exit","IconActive":true,"IconValue":"circle-xmark","Color":-1}]',
        'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
    ];

    /**
     * @var array<string,mixed> Working Presentation (Value)
     */
    private const T2MVC_PRESENTATION_WORKING = [
        'COLOR'               => -1,
        'CONTENT_COLOR'       => -1,
        'DISPLAY_TYPE'        => 0,
        'ICON'                => 'vacuum-robot',
        'MULTILINE'           => false,
        'OPTIONS'             => '[{"Value":"standby","Caption":"Standby","IconActive":false,"IconValue":""},{"Value":"smart_clean","Caption":"Smart cleaning","IconActive":false,"IconValue":""},{"Value":"wall_clean","Caption":"Edge cleaning","IconActive":false,"IconValue":""},{"Value":"spot_clean","Caption":"Spot cleaning","IconActive":false,"IconValue":""},{"Value":"mop_clean","Caption":"Mopping and cleaning","IconActive":false,"IconValue":""},{"Value":"goto_charge","Caption":"Go charging","IconActive":false,"IconValue":""},{"Value":"charging","Caption":"Charging","IconActive":false,"IconValue":""},{"Value":"charge_done","Caption":"Charged","IconActive":false,"IconValue":""},{"Value":"paused","Caption":"Paused","IconActive":false,"IconValue":""},{"Value":"cleaning","Caption":"Cleaning","IconActive":false,"IconValue":""},{"Value":"sleep","Caption":"Sleep","IconActive":false,"IconValue":""}]',
        'PERCENTAGE'          => false,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
        'PREVIEW_STYLE'       => 1,
        'SHOW_PREVIEW'        => true,
        'SUFFIX'              => '',
        'USAGE_TYPE'          => 0,
    ];

    /**
     * @var array<string,mixed> Battery Presentation (Value)
     */
    private const T2MVC_PRESENTATION_BATTERY = [
        'COLOR'               => -1,
        'CONTENT_COLOR'       => -1,
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'DISPLAY_TYPE'        => 0,
        'ICON'                => 'Battery',
        'INTERVALS'           => '[]',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 100,
        'MIN'                 => 0,
        'MULTILINE'           => false,
        'PERCENTAGE'          => true,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
        'PREVIEW_STYLE'       => 1,
        'SHOW_PREVIEW'        => true,
        'SUFFIX'              => ' %',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 0,
    ];

    /**
     * @var array<string,mixed> Valve Presentation (Value)
     */
    private const T2MVC_PRESENTATION_VALVE = [
        'COLOR'               => -1,
        'CONTENT_COLOR'       => -1,
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'DISPLAY_TYPE'        => 0,
        'ICON'                => 'Gauge',
        'INTERVALS'           => '[]',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 100,
        'MIN'                 => 0,
        'MULTILINE'           => false,
        'PERCENTAGE'          => true,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
        'PREVIEW_STYLE'       => 1,
        'SHOW_PREVIEW'        => true,
        'SUFFIX'              => ' %',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 0,
    ];

    /**
     * @var array<string,mixed> Clean Area Presentation (Value)
     */
    private const T2MVC_PRESENTATION_CLEAN_AREA = [
        'COLOR'               => -1,
        'CONTENT_COLOR'       => -1,
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'DISPLAY_TYPE'        => 0,
        'ICON'                => 'map',
        'INTERVALS'           => '[]',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 9999,
        'MIN'                 => 0,
        'PERCENTAGE'          => false,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
        'PREVIEW_STYLE'       => 1,
        'SHOW_PREVIEW'        => true,
        'SUFFIX'              => ' m²',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 0,
    ];

    /**
     * @var array<string,mixed> Clean Time Presentation (Value)
     */
    private const T2MVC_PRESENTATION_CLEAN_TIME = [
        'COLOR'               => -1,
        'CONTENT_COLOR'       => -1,
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'DISPLAY_TYPE'        => 0,
        'ICON'                => 'timer',
        'INTERVALS'           => '[]',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 9999,
        'MIN'                 => 0,
        'MULTILINE'           => false,
        'PERCENTAGE'          => false,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
        'PREVIEW_STYLE'       => 1,
        'SHOW_PREVIEW'        => true,
        'SUFFIX'              => ' min',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 0,
    ];

    /**
     * @var array<string,mixed> Suction Presentation (Enumeration)
     */
    private const T2MVC_PRESENTATION_SUCTION = [
        'DISPLAY'      => 0,
        'ICON'         => 'vacuum',
        'LAYOUT'       => 0,
        'OPTIONS'      => '[{"Value":"strong","Caption":"Strong","IconActive":false,"IconValue":"","Color":-1},{"Value":"normal","Caption":"Normal","IconActive":false,"IconValue":"","Color":-1},{"Value":"gentle","Caption":"Gentle","IconActive":false,"IconValue":"","Color":-1}]',
        'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
    ];

    /**
     * @var array<string,mixed> Volume Presentation (Slider)
     */
    private const T2MVC_PRESENTATION_VOLUME = [
        'CUSTOM_GRADIENT'     => '[]',
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'GRADIENT_TYPE'       => 0,
        'ICON'                => 'Speaker',
        'INTERVALS'           => '[]',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 100,
        'MIN'                 => 0,
        'PERCENTAGE'          => true,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_SLIDER,
        'STEP_SIZE'           => 1.0,
        'SUFFIX'              => ' %',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 3,
    ];

    /**
     * @var array<string,mixed> Language Presentation (Enumeration)
     */
    private const T2MVC_PRESENTATION_LANG = [
        'DISPLAY'      => 0,
        'ICON'         => 'language',
        'LAYOUT'       => 0,
        'OPTIONS'      => '[{"Value":"english","Caption":"English","IconActive":false,"IconValue":"","Color":-1},{"Value":"german","Caption":"German","IconActive":false,"IconValue":"","Color":-1},{"Value":"french","Caption":"French","IconActive":false,"IconValue":"","Color":-1},{"Value":"russian","Caption":"Russian","IconActive":false,"IconValue":"","Color":-1},{"Value":"spanish","Caption":"Spanish","IconActive":false,"IconValue":"","Color":-1},{"Value":"italian","Caption":"Italian","IconActive":false,"IconValue":"","Color":-1}]',
        'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
    ];

    /**
     * @var array<string,mixed> Clean speed Presentation (Enumeration)
     */
    private const T2MVC_PRESENTATION_CLEAN_SPEED = [
        'DISPLAY'      => 0,
        'ICON'         => '',
        'LAYOUT'       => 0,
        'OPTIONS'      => '[{"Value":"careful_clean","Caption":"Careful clean","IconValue":"turtle","IconActive":true,"Color":-1},{"Value":"speed_clean","Caption":"Speed clean","IconValue":"rabbit-running","IconActive":true,"Color":-1}]',
        'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
    ];

    /**
     * @var array<string,mixed> State Presentation (Value)
     */
    private const T2MVC_PRESENTATION_STATE = [
        'COLOR'         => -1,
        'CONTENT_COLOR' => -1,
        'DISPLAY_TYPE'  => 0,
        'ICON'          => '',
        'MULTILINE'     => false,
        'OPTIONS'       => '[{"Caption":"Offline","ColorActive":true,"ColorValue":16711680,"ContentColorActive":false,"ContentColorValue":-1,"IconActive":true,"IconValue":"signal-slash","Value":"offline"},{"Caption":"Online","ColorActive":true,"ColorValue":65280,"ContentColorActive":false,"ContentColorValue":-1,"IconActive":true,"IconValue":"signal","Value":"online"},{"Caption":"Undefiniert","ColorActive":true,"ColorValue":255,"ContentColorActive":false,"ContentColorValue":-1,"IconActive":true,"IconValue":"signal-slash","Value":"undefine"}]',
        'PERCENTAGE'    => false,
        'PREFIX'        => '',
        'PRESENTATION'  => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
        'PREVIEW_STYLE' => 1,
        'SHOW_PREVIEW'  => true,
        'SUFFIX'        => '',
        'USAGE_TYPE'    => 0,
    ];

    // -------------------------------------------------------------------------
    // Methods
    // -------------------------------------------------------------------------

    /**
     * In contrast to Construct, this function is called only once when creating the instance and starting Symcon.
     * Therefore, status variables and module properties which the module requires permanently should be created here.
     *
     * @return void
     */
    public function Create(): void
    {
        //Never delete this line!
        parent::Create();

        // Device-Topic (Name)
        $this->RegisterPropertyString('MQTTBaseTopic', 'tuya2mqtt');
        $this->RegisterPropertyString('MQTTTopic', '');

        // Automatically connect to the MQTT server/splitter instance
        if ((float) IPS_GetKernelVersion() < 8.2) {
            $this->ConnectParent(self::GUID_MQTT_IO);
        }

        // Request all states after system start
        $this->RegisterMessage(0, IPS_KERNELSTARTED);
    }

    /**
     * This function is called when deleting the instance during operation and when updating via "Module Control".
     * The function is not called when exiting Symcon.
     *
     * @return void
     */
    public function Destroy(): void
    {
        //Never delete this line!
        parent::Destroy();
    }

    /**
     * The content can be overwritten in order to transfer a self-created configuration page.
     * This way, content can be generated dynamically.
     * In this case, the "form.json" on the file system is completely ignored.
     *
     * @return string Content of the configuration page.
     */
    public function GetConfigurationForm(): string
    {
        // Get Form
        $form = json_decode(file_get_contents(__DIR__ . '/form.json'), true);
        //$this->LogDebug(__FUNCTION__, $form);
        return json_encode($form);
    }

    /**
     * Is executed when "Apply" is pressed on the configuration page and immediately after the instance has been created.
     *
     * @return void
     */
    public function ApplyChanges(): void
    {
        //Never delete this line!
        parent::ApplyChanges();

        $base = $this->GetBaseTopic();
        $topic = $this->ReadPropertyString('MQTTTopic');

        // Check setup
        if (empty($base) || empty($topic)) {
            // Receive nothing as long as the topics are not configured
            $this->SetReceiveDataFilter('^$');
            $this->SetStatus(201);
            return;
        } else {
            // Set filter (device topics and bridge status)
            $filter = '.*(' . preg_quote($base . '/' . $topic . '/') . '|' . preg_quote($base . '/bridge/status') . ').*';
            $this->LogDebug(__FUNCTION__, 'Filter: ' . $filter);
            $this->SetReceiveDataFilter($filter);
        }

        // Initialize
        // Statusvariable (SyncProfile)
        $es = @$this->GetIDForIdent('status');

        $mode = $this->TranslatePresentation(self::T2MVC_PRESENTATION_MODE, 'OPTIONS', 'Caption');
        $direction = $this->TranslatePresentation(self::T2MVC_PRESENTATION_DIRECTION, 'OPTIONS', 'Caption');
        $working = $this->TranslatePresentation(self::T2MVC_PRESENTATION_WORKING, 'OPTIONS', 'Caption');
        $suction = $this->TranslatePresentation(self::T2MVC_PRESENTATION_SUCTION, 'OPTIONS', 'Caption');
        $language = $this->TranslatePresentation(self::T2MVC_PRESENTATION_LANG, 'OPTIONS', 'Caption');
        $speed = $this->TranslatePresentation(self::T2MVC_PRESENTATION_CLEAN_SPEED, 'OPTIONS', 'Caption');
        $state = $this->TranslatePresentation(self::T2MVC_PRESENTATION_STATE, 'OPTIONS', 'Caption');

        // Maintain variables
        $pos = 0;
        $this->MaintainVariable('power', $this->Translate('Power'), 0, self::T2MVC_PRESENTATION_SWITCH, $pos++, true);
        $this->MaintainVariable('mode', $this->Translate('Mode'), 3, $mode, $pos++, true);
        $this->MaintainVariable('direction_control', $this->Translate('Direction control'), 3, $direction, $pos++, true);
        $this->MaintainVariable('working_status', $this->Translate('Working status'), 3, $working, $pos++, true);
        $this->MaintainVariable('battery_left', $this->Translate('Battery left'), 1, self::T2MVC_PRESENTATION_BATTERY, $pos++, true);
        $this->MaintainVariable('edge_brush', $this->Translate('Edge brush'), 1, self::T2MVC_PRESENTATION_VALVE, $pos++, true);
        $this->MaintainVariable('roll_brush', $this->Translate('Roll brush'), 1, self::T2MVC_PRESENTATION_VALVE, $pos++, true);
        $this->MaintainVariable('filter', $this->Translate('Filter'), 1, self::T2MVC_PRESENTATION_VALVE, $pos++, true);
        $this->MaintainVariable('suction', $this->Translate('Suction'), 3, $suction, $pos++, true);
        $this->MaintainVariable('volume_set', $this->Translate('Volume'), 1, self::T2MVC_PRESENTATION_VOLUME, $pos++, true);
        $this->MaintainVariable('clean_speed', $this->Translate('Clean speed'), 3, $speed, $pos++, true);
        $this->MaintainVariable('clean_area', $this->Translate('Clean area'), 1, self::T2MVC_PRESENTATION_CLEAN_AREA, $pos++, true);
        $this->MaintainVariable('clean_time', $this->Translate('Clean time'), 1, self::T2MVC_PRESENTATION_CLEAN_TIME, $pos++, true);
        $this->MaintainVariable('status', $this->Translate('Status'), 3, $state, $pos++, true);
        $this->MaintainVariable('language', $this->Translate('Language'), 3, $language, $pos++, true);

        // Maintain actions
        $this->MaintainAction('language', true);
        $this->MaintainAction('power', true);
        $this->MaintainAction('mode', true);
        $this->MaintainAction('direction_control', true);
        $this->MaintainAction('suction', true);
        $this->MaintainAction('volume_set', true);
        $this->MaintainAction('clean_speed', true);

        // Init on first time
        if (!$es) {
            $this->SetValueString('status', 'undefine');
        }

        // All ready
        $this->SetStatus(102);

        // tuya2mqtt only publishes changed values, so request the current states
        if (IPS_GetKernelRunlevel() === KR_READY) {
            $this->RequestStates();
        }
    }

    /**
     * The content of the function can be overwritten in order to carry out own reactions to certain messages.
     * The function is only called for registered MessageIDs/SenderIDs combinations.
     *
     * @param int              $timestamp Continuous counter timestamp
     * @param int              $sender    Sender ID
     * @param int              $message   ID of the message
     * @param array<int,mixed> $data      Data of the message
     * @return void
     */
    public function MessageSink(int $timestamp, int $sender, int $message, array $data): void
    {
        switch ($message) {
            case IPS_KERNELSTARTED:
                $this->LogDebug(__FUNCTION__, 'Kernel started -> request states!');
                $this->RequestStates();
                break;
        }
    }

    /**
     * Is called when, for example, a button is clicked in the visualization.
     *
     * @param string $ident Ident of the variable
     * @param mixed $value The value to be set
     * @return void
     */
    public function RequestAction(string $ident, mixed $value): void
    {
        // Debug output
        $this->LogDebug(__FUNCTION__, $ident . ' => ' . $value);
        switch ($ident) {
            case 'get-states':
                $this->SendMQTT('command', $ident);
                break;
            case 'power':
                // boolean
                $this->SendMQTT($ident . '/command', $value ? 'true' : 'false');
                break;
            case 'volume_set':
                // integer
                $this->SendMQTT($ident . '/command', strval($value));
                break;
            case 'mode':
            case 'direction_control':
            case 'suction':
            case 'clean_speed':
            case 'language':
                // string
                $this->SendMQTT($ident . '/command', strval($value));
                break;
            default:
                $this->LogDebug(__FUNCTION__, 'ERROR!!!');
                break;
        }
        //$this->SetValue($ident, $value);
    }

    /**
     * This function is called by Symcon and processes sent data and, if necessary, forwards it to
     * all child instances. Data can be sent using the SendDataToChildren function.
     *
     * @param string $json Data package in JSON format
     *
     * @return string Optional response to the parent instance
     */
    public function ReceiveData(string $json): string
    {
        $data = json_decode($json);

        $topic = $data->Topic;
        $payload = hex2bin($data->Payload);
        $this->LogDebug(__FUNCTION__, 'Received Topic: ' . $topic . ' Payload: ' . $payload);

        // Bridge status (MQTT last will), the retained device status may be outdated if tuya2mqtt crashes
        if ($topic === $this->GetBaseTopic() . '/bridge/status') {
            if ($payload === 'offline') {
                $this->SetValueString('status', 'offline');
            }
            return '';
        }
        if (fnmatch('*/status', $topic)) {
            $this->SetValueString('status', strval($payload));
        }
        if (fnmatch('*/power', $topic)) {
            $this->SetValueBoolean('power', $payload == 'on' ? true : false);
        }
        if (fnmatch('*/mode', $topic)) {
            $this->SetValueString('mode', strval($payload));
        }
        if (fnmatch('*/direction_control', $topic)) {
            $this->SetValueString('direction_control', strval($payload));
        }
        if (fnmatch('*/working_status', $topic)) {
            $this->SetValueString('working_status', strval($payload));
        }
        if (fnmatch('*/battery_left', $topic)) {
            $this->SetValueInteger('battery_left', intval($payload));
        }
        if (fnmatch('*/edge_brush', $topic)) {
            $this->SetValueInteger('edge_brush', intval($payload));
        }
        if (fnmatch('*/roll_brush', $topic)) {
            $this->SetValueInteger('roll_brush', intval($payload));
        }
        if (fnmatch('*/filter', $topic)) {
            $this->SetValueInteger('filter', intval($payload));
        }
        if (fnmatch('*/suction', $topic)) {
            $this->SetValueString('suction', strval($payload));
        }
        if (fnmatch('*/clean_area', $topic)) {
            $this->SetValueInteger('clean_area', intval($payload));
        }
        if (fnmatch('*/clean_time', $topic)) {
            $this->SetValueInteger('clean_time', intval($payload));
        }
        if (fnmatch('*/clean_speed', $topic)) {
            $this->SetValueString('clean_speed', strval($payload));
        }
        if (fnmatch('*/volume_set', $topic)) {
            $this->SetValueInteger('volume_set', intval($payload));
        }
        if (fnmatch('*/language', $topic)) {
            $this->SetValueString('language', strval($payload));
        }
        return '';
    }

    /**
     * Send command to MQTT server.
     *
     * @param string $topic Topic name
     * @param string $payload Payload data
     *
     * @return bool True if send successful, otherwise false.
     */
    protected function SendMQTT(string $topic, string $payload): bool
    {
        $resultServer = true;
        // MQTT Server
        $server['DataID'] = self::GUID_MQTT_TX;
        $server['PacketType'] = 3;
        $server['QualityOfService'] = 0;
        $server['Retain'] = false;
        $server['Topic'] = $this->GetBaseTopic() . '/' . $this->ReadPropertyString('MQTTTopic') . '/' . $topic;
        $server['Payload'] = bin2hex($payload);
        $json = json_encode($server, JSON_UNESCAPED_SLASHES);
        $this->LogDebug(__FUNCTION__ . 'MQTT Server', $json);
        $resultServer = @$this->SendDataToParent($json);
        return $resultServer !== '';
    }

    /**
     * Returns the configured base topic without trailing slash.
     *
     * @return string Base topic
     */
    private function GetBaseTopic(): string
    {
        return rtrim($this->ReadPropertyString('MQTTBaseTopic'), '/');
    }

    /**
     * Requests all current states of the device (get-states).
     *
     * @return void
     */
    private function RequestStates(): void
    {
        if ($this->GetStatus() !== 102 || !$this->HasActiveParent()) {
            return;
        }
        $this->SendMQTT('command', 'get-states');
    }
}