<?php

declare(strict_types=1);

/** Generell funktions */
require_once __DIR__ . '/../libs/_traits.php';

/** Namespaced traits */
use Wilkware\LocalTuya\DebugHelper;
use Wilkware\LocalTuya\FormHelper;
use Wilkware\LocalTuya\VariableHelper;

/**
 * CLASS CeilingFan
 */
class CeilingFan extends IPSModuleStrict
{
    // -------------------------------------------------------------------------
    // Traits
    // -------------------------------------------------------------------------

    use DebugHelper;
    use FormHelper;
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
    private const T2MCF_PRESENTATION_SWITCH = [
        'GLOW_COLOR'     => 16771899,
        'GLOW_INTENSITY' => 50,
        'ICON_FALSE'     => 'lightbulb-on',
        'ICON_TRUE'      => 'lightbulb',
        'PRESENTATION'   => VARIABLE_PRESENTATION_SWITCH,
        'USAGE_TYPE'     => 0,
        'USE_ICON_FALSE' => true,
    ];

    /**
     * @var array<string,mixed> ColorTemperature Presentation (Slider)
     */
    private const T2MCF_PRESENTATION_COLOR = [
        'CUSTOM_GRADIENT'     => '[{"Value":1000,"Color":16761095},{"Value":500,"Color":16777215},{"Value":0,"Color":1155315}]',
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'GRADIENT_TYPE'       => 3,
        'ICON'                => 'sliders',
        'INTERVALS'           => '[{"IntervalMinValue":0,"IntervalMaxValue":499,"ConstantActive":true,"ConstantValue":"Cool","ConversionFactor":1,"IconActive":true,"IconValue":"dial-min","PrefixActive":false,"PrefixValue":"","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0},{"IntervalMinValue":500,"IntervalMaxValue":999,"ConstantActive":true,"ConstantValue":"Neutral","ConversionFactor":1,"IconActive":true,"IconValue":"dial-med","PrefixActive":false,"PrefixValue":"","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0},{"IntervalMinValue":1000,"IntervalMaxValue":1000,"ConstantActive":true,"ConstantValue":"Warm","ConversionFactor":1,"IconActive":true,"IconValue":"dial-max","PrefixActive":false,"PrefixValue":"","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0}]',
        'INTERVALS_ACTIVE'    => true,
        'MAX'                 => 1000,
        'MIN'                 => 0,
        'PERCENTAGE'          => false,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_SLIDER,
        'STEP_SIZE'           => 500.0,
        'SUFFIX'              => '',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 2,
    ];

    /**
     * @var array<string,mixed> Fan Presentation (Switch)
     */
    private const T2MCF_PRESENTATION_FAN = [
        'GLOW_COLOR'     => 16771899,
        'GLOW_INTENSITY' => 50,
        'ICON_FALSE'     => 'power-off',
        'ICON_TRUE'      => 'fan',
        'PRESENTATION'   => VARIABLE_PRESENTATION_SWITCH,
        'USAGE_TYPE'     => 0,
        'USE_ICON_FALSE' => false,
    ];

    /**
     * @var array<string,mixed> Speed Presentation (Slider)
     */
    private const T2MCF_PRESENTATION_SPEED = [
        'CUSTOM_GRADIENT'     => '[{"Value":1,"Color":49151},{"Value":2,"Color":4251856},{"Value":3,"Color":8388564},{"Value":4,"Color":11403055},{"Value":5,"Color":16766720},{"Value":6,"Color":16729344}]',
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'GRADIENT_TYPE'       => 3,
        'ICON'                => 'gauge',
        'INTERVALS'           => '[]',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 6,
        'MIN'                 => 1,
        'PERCENTAGE'          => false,
        'PREFIX'              => 'Level ',
        'PRESENTATION'        => VARIABLE_PRESENTATION_SLIDER,
        'STEP_SIZE'           => 1.0,
        'SUFFIX'              => '',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 2,
    ];

    /**
     * @var array<string,mixed> Direction Presentation (Enumeration)
     */
    private const T2MCF_PRESENTATION_DIRECTION = [
        'DISPLAY'      => 0,
        'ICON'         => 'compass',
        'LAYOUT'       => 0,
        'OPTIONS'      => '[{"Value":"forward","Caption":"Forward","IconActive":true,"IconValue":"arrows-rotate","Color":-1},{"Value":"reverse","Caption":"Reverse","IconActive":true,"IconValue":"arrows-rotate-reverse","Color":-1}]',
        'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
    ];

    /**
     * @var array<string,mixed> Beep Presentation (Switch)
     */
    private const T2MCF_PRESENTATION_BEEP = [
        'GLOW_COLOR'     => 16771899,
        'GLOW_INTENSITY' => 50,
        'ICON_FALSE'     => 'bell',
        'ICON_TRUE'      => 'bell-on',
        'PRESENTATION'   => VARIABLE_PRESENTATION_SWITCH,
        'USAGE_TYPE'     => 0,
        'USE_ICON_FALSE' => true,
    ];

    /**
     * @var array<string,mixed> Timer Presentation (Slider)
     */
    private const T2MCF_PRESENTATION_TIMER = [
        'CUSTOM_GRADIENT'     => '[]',
        'DECIMAL_SEPARATOR'   => 'Client',
        'DIGITS'              => 0,
        'GRADIENT_TYPE'       => 0,
        'ICON'                => 'timer',
        'INTERVALS'           => '[]',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 540,
        'MIN'                 => 0,
        'PERCENTAGE'          => false,
        'PREFIX'              => '',
        'PRESENTATION'        => VARIABLE_PRESENTATION_SLIDER,
        'STEP_SIZE'           => 1.0,
        'SUFFIX'              => ' min',
        'THOUSANDS_SEPARATOR' => '',
        'USAGE_TYPE'          => 5,
    ];

    /**
     * @var array<string,mixed> State Presentation (Value)
     */
    private const T2MCF_PRESENTATION_STATE = [
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
        // Extract Version
        $ins = IPS_GetInstance($this->InstanceID);
        $mod = IPS_GetModule($ins['ModuleInfo']['ModuleID']);
        $lib = IPS_GetLibrary($mod['LibraryID']);
        $version = sprintf('v%s.%d', $lib['Version'], $lib['Build']);
        $this->ModifyFormElement($form['actions'], 'Version', function (array &$element) use ($version): void
        {
            $element['caption'] = $version;
        });
        // return form
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

        // Presentations
        $color = $this->TranslatePresentation(self::T2MCF_PRESENTATION_COLOR, 'INTERVALS', 'ConstantValue');
        $speed = $this->TranslatePresentation(self::T2MCF_PRESENTATION_SPEED);
        $direction = $this->TranslatePresentation(self::T2MCF_PRESENTATION_DIRECTION, 'OPTIONS', 'Caption');
        $state = $this->TranslatePresentation(self::T2MCF_PRESENTATION_STATE, 'OPTIONS', 'Caption');

        // Maintain variables
        $pos = 0;
        $this->MaintainVariable('light', $this->Translate('Light'), 0, self::T2MCF_PRESENTATION_SWITCH, $pos++, true);
        $this->MaintainVariable('color_temp', $this->Translate('Color temp'), 1, $color, $pos++, true);
        $this->MaintainVariable('fan', $this->Translate('Fan'), 0, self::T2MCF_PRESENTATION_FAN, $pos++, true);
        $this->MaintainVariable('speed', $this->Translate('Speed'), 1, $speed, $pos++, true);
        $this->MaintainVariable('direction', $this->Translate('Direction'), 3, $direction, $pos++, true);
        $this->MaintainVariable('countdown_left', $this->Translate('Countdown left'), 1, self::T2MCF_PRESENTATION_TIMER, $pos++, true);
        $this->MaintainVariable('beep', $this->Translate('Beep'), 0, self::T2MCF_PRESENTATION_BEEP, $pos++, true);
        $this->MaintainVariable('status', $this->Translate('Status'), 3, $state, $pos++, true);

        // Maintain actions
        $this->MaintainAction('light', true);
        $this->MaintainAction('color_temp', true);
        $this->MaintainAction('fan', true);
        $this->MaintainAction('speed', true);
        $this->MaintainAction('direction', true);
        $this->MaintainAction('countdown_left', true);
        $this->MaintainAction('beep', true);

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
            case 'light':
            case 'fan':
            case 'beep':
                // boolean
                $this->SendMQTT($ident . '/command', $value ? 'true' : 'false');
                break;
            case 'speed':
                $this->SendMQTT($ident . '/command', strval($value));
                // The fan starts with a speed change, but the device keeps reporting it as off
                // (the remote control sends both), so switch it on explicitly
                if (!$this->GetValue('fan')) {
                    $this->SendMQTT('fan/command', 'true');
                }
                break;
            case 'color_temp':
            case 'countdown_left':
                // integer
                $this->SendMQTT($ident . '/command', strval($value));
                break;
            case 'direction':
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
        if (fnmatch('*/light', $topic)) {
            $this->SetValueBoolean('light', $payload == 'on' ? true : false);
        }
        if (fnmatch('*/color_temp', $topic)) {
            $this->SetValueInteger('color_temp', intval($payload));
        }
        if (fnmatch('*/fan', $topic)) {
            $this->SetValueBoolean('fan', $payload == 'on' ? true : false);
        }
        if (fnmatch('*/speed', $topic)) {
            $this->SetValueInteger('speed', intval($payload));
        }
        if (fnmatch('*/direction', $topic)) {
            $this->SetValueString('direction', strval($payload));
        }
        if (fnmatch('*/countdown_left', $topic)) {
            $this->SetValueInteger('countdown_left', intval($payload));
        }
        if (fnmatch('*/beep', $topic)) {
            $this->SetValueBoolean('beep', $payload == 'on' ? true : false);
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
        $this->LogDebug(__FUNCTION__, $json);
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