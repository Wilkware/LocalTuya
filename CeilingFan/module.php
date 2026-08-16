<?php

declare(strict_types=1);

/** Generell funktions */
require_once __DIR__ . '/../libs/_traits.php';

/** Namespaced traits */
use Wilkware\LocalTuya\DebugHelper;
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
        'PRESENTATION'   => VARIABLE_PRESENTATION_SWITCH,
        'USE_ICON_FALSE' => true,
        'USAGE_TYPE'     => 0,
        'ICON_TRUE'      => 'lightbulb',
        'ICON_FALSE'     => 'lightbulb-on',
        'GLOW_INTENSITY' => 50,
        'GLOW_COLOR'     => 16771899,
    ];

    /**
     * @var array<string,mixed> ColorTemperature Presentation (Slider)
     */
    private const T2MCF_PRESENTATION_COLOR = [
        'PRESENTATION'        => VARIABLE_PRESENTATION_SLIDER,
        'USAGE_TYPE'          => 2,
        'THOUSANDS_SEPARATOR' => '',
        'DECIMAL_SEPARATOR'   => 'Client',
        'PERCENTAGE'          => false,
        'DIGITS'              => 0,
        'INTERVALS'           => '[{"IntervalMinValue":0,"IntervalMaxValue":499,"ConstantActive":true,"ConstantValue":"Cool","ConversionFactor":1,"IconActive":true,"IconValue":"dial-min","PrefixActive":false,"PrefixValue":"","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0},{"IntervalMinValue":500,"IntervalMaxValue":999,"ConstantActive":true,"ConstantValue":"Neutral","ConversionFactor":1,"IconActive":true,"IconValue":"dial-med","PrefixActive":false,"PrefixValue":"","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0},{"IntervalMinValue":1000,"IntervalMaxValue":1000,"ConstantActive":true,"ConstantValue":"Warm","ConversionFactor":1,"IconActive":true,"IconValue":"dial-max","PrefixActive":false,"PrefixValue":"","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0}]',
        'ICON'                => 'sliders',
        'INTERVALS_ACTIVE'    => true,
        'MAX'                 => 1000,
        'GRADIENT_TYPE'       => 3,
        'MIN'                 => 0,
        'CUSTOM_GRADIENT'     => '[{"Value":1000,"Color":16761095},{"Value":500,"Color":16777215},{"Value":0,"Color":1155315}]',
        'PREFIX'              => '',
        'STEP_SIZE'           => 500.0,
        'SUFFIX'              => '',
    ];

    /**
     * @var array<string,mixed> Fan Presentation (Switch)
     */
    private const T2MCF_PRESENTATION_FAN = [
        'PRESENTATION'   => VARIABLE_PRESENTATION_SWITCH,
        'USE_ICON_FALSE' => false,
        'USAGE_TYPE'     => 0,
        'ICON_TRUE'      => 'fan',
        'ICON_FALSE'     => 'power-off',
        'GLOW_INTENSITY' => 50,
        'GLOW_COLOR'     => 16771899,
    ];

    /**
     * @var array<string,mixed> Speed Presentation (Slider)
     */
    private const T2MCF_PRESENTATION_SPEED = [
        'PRESENTATION'        => VARIABLE_PRESENTATION_SLIDER,
        'USAGE_TYPE'          => 2,
        'THOUSANDS_SEPARATOR' => '',
        'DECIMAL_SEPARATOR'   => 'Client',
        'PERCENTAGE'          => false,
        'DIGITS'              => 0,
        'INTERVALS'           => '[]',
        'ICON'                => 'gauge',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 6,
        'GRADIENT_TYPE'       => 3,
        'MIN'                 => 1,
        'CUSTOM_GRADIENT'     => '[{"Value":1,"Color":49151},{"Value":2,"Color":4251856},{"Value":3,"Color":8388564},{"Value":4,"Color":11403055},{"Value":5,"Color":16766720},{"Value":6,"Color":16729344}]',
        'PREFIX'              => 'Stufe ',
        'STEP_SIZE'           => 1.0,
        'SUFFIX'              => '',
    ];

    /**
     * @var array<string,mixed> Direction Presentation (Enumeration)
     */
    private const T2MCF_PRESENTATION_DIRECTION = [
        'PRESENTATION' => VARIABLE_PRESENTATION_ENUMERATION,
        'OPTIONS'      => '[{"Value":"forward","Caption":"Forward","IconActive":true,"IconValue":"arrows-rotate","Color":-1},{"Value":"reverse","Caption":"Reverse","IconActive":true,"IconValue":"arrows-rotate-reverse","Color":-1}]',
        'LAYOUT'       => 0,
        'ICON'         => 'compass',
        'DISPLAY'      => 0,
    ];

    /**
     * @var array<string,mixed> Beep Presentation (Switch)
     */
    private const T2MCF_PRESENTATION_BEEP = [
        'PRESENTATION'   => VARIABLE_PRESENTATION_SWITCH,
        'USE_ICON_FALSE' => true,
        'USAGE_TYPE'     => 0,
        'ICON_TRUE'      => 'bell-on',
        'ICON_FALSE'     => 'bell',
        'GLOW_INTENSITY' => 50,
        'GLOW_COLOR'     => 16771899,
    ];

    /**
     * @var array<string,mixed> Timer Presentation (Slider)
     */
    private const T2MCF_PRESENTATION_TIMER = [
        'PRESENTATION'        => VARIABLE_PRESENTATION_SLIDER,
        'USAGE_TYPE'          => 5,
        'THOUSANDS_SEPARATOR' => '',
        'DECIMAL_SEPARATOR'   => 'Client',
        'PERCENTAGE'          => false,
        'DIGITS'              => 0,
        'INTERVALS'           => '[]',
        'ICON'                => 'timer',
        'INTERVALS_ACTIVE'    => false,
        'MAX'                 => 540,
        'GRADIENT_TYPE'       => 0,
        'MIN'                 => 0,
        'CUSTOM_GRADIENT'     => '[]',
        'PREFIX'              => '',
        'STEP_SIZE'           => 1.0,
        'SUFFIX'              => ' min',
    ];

    /**
     * @var array<string,mixed> State Presentation (Value)
     */
    private const T2MCF_PRESENTATION_STATE = [
        'PRESENTATION'        => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
        'USAGE_TYPE'          => 0,
        'THOUSANDS_SEPARATOR' => '',
        'SHOW_PREVIEW'        => true,
        'SUFFIX'              => '',
        'COLOR'               => -1,
        'PREFIX'              => '',
        'CONTENT_COLOR'       => -1,
        'MAX'                 => 0,
        'MULTILINE'           => false,
        'DECIMAL_SEPARATOR'   => 'Client',
        'PERCENTAGE'          => false,
        'DIGITS'              => 0,
        'INTERVALS'           => '[]',
        'DISPLAY_TYPE'        => 0,
        'ICON'                => '',
        'INTERVALS_ACTIVE'    => true,
        'PREVIEW_STYLE'       => 1,
        'MIN'                 => 0,
        'OPTIONS'             => '[{"Value":"offline","Caption":"Offline","IconActive":true,"IconValue":"signal-slash","ColorActive":true,"ColorValue":16711680},{"Value":"online","Caption":"Online","IconActive":true,"IconValue":"signal","ColorActive":true,"ColorValue":65280},{"Value":"undefine","Caption":"Undefine","IconActive":true,"IconValue":"signal-slash","ColorActive":true,"ColorValue":255}]',
    ];

    // -------------------------------------------------------------------------
    // Methods
    // -------------------------------------------------------------------------

    /**
     * In contrast to Construct, this function is called only once when creating the instance and starting IP-Symcon.
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
    }

    /**
     * This function is called when deleting the instance during operation and when updating via "Module Control".
     * The function is not called when exiting IP-Symcon.
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

        $base = $this->ReadPropertyString('MQTTBaseTopic');
        $topic = $this->ReadPropertyString('MQTTTopic');

        // Check setup
        if (empty($base) || empty($topic)) {
            $this->SetStatus(201);
            return;
        } else {
            // Set filter
            $filter = preg_quote($this->ReadPropertyString('MQTTBaseTopic') . '/' . $this->ReadPropertyString('MQTTTopic'));
            $this->LogDebug(__FUNCTION__, 'Filter: .*' . $filter . '.*');
            $this->SetReceiveDataFilter('.*' . $filter . '.*');
        }

        // Initialize
        // Statusvariable (SyncProfile)
        $es = @$this->GetIDForIdent('status');

        // Presentations
        $color = $this->TranslatePresentation(self::T2MCF_PRESENTATION_COLOR, 'OPTIONS', 'Caption');
        $speed = $this->TranslatePresentation(self::T2MCF_PRESENTATION_SPEED);
        $state = $this->TranslatePresentation(self::T2MCF_PRESENTATION_STATE, 'OPTIONS', 'Caption');

        // Maintain variables
        $pos = 0;
        $this->MaintainVariable('light', $this->Translate('Light'), 0, self::T2MCF_PRESENTATION_SWITCH, $pos++, true);
        $this->MaintainVariable('color_temp', $this->Translate('Color temp'), 1, $color, $pos++, true);
        $this->MaintainVariable('fan', $this->Translate('Fan'), 0, self::T2MCF_PRESENTATION_FAN, $pos++, true);
        $this->MaintainVariable('speed', $this->Translate('Speed'), 1, $speed, $pos++, true);
        $this->MaintainVariable('direction', $this->Translate('Direction'), 3, self::T2MCF_PRESENTATION_DIRECTION, $pos++, true);
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
            case 'color_temp':
            case 'speed':
            case 'countdown_left':
                // integer
                $this->SendMQTT($ident . '/command', strval($value));
                break;
            case 'direction':
                // string
                $this->SendMQTT($ident . '/command', $value);
                break;
            default:
                $this->LogDebug(__FUNCTION__, 'ERROR!!!');
                break;
        }
        //$this->SetValue($ident, $value);
    }

    /**
     * This function is called by IP-Symcon and processes sent data and, if necessary, forwards it to
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
        $server['Topic'] = $this->ReadPropertyString('MQTTBaseTopic') . '/' . $this->ReadPropertyString('MQTTTopic') . '/' . $topic;
        $server['Payload'] = bin2hex($payload);
        $json = json_encode($server, JSON_UNESCAPED_SLASHES);
        $this->LogDebug(__FUNCTION__, $json);
        $resultServer = @$this->SendDataToParent($json);
        return $resultServer !== '';
    }
}