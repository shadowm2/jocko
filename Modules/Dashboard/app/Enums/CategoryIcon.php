<?php

namespace Modules\Dashboard\Enums;

use App\Traits\UseBaseEnum;

enum CategoryIcon: string
{
    use UseBaseEnum;
    // General
    case Folder = 'folder';
    case FolderOpen = 'folder-open';
    case FolderPlus = 'folder-plus';
    case FolderMinus = 'folder-minus';
    case Document = 'document';
    case DocumentText = 'document-text';
    case Clipboard = 'clipboard';
    case ArchiveBox = 'archive-box';
    case ArchiveBoxArrowDown = 'archive-box-arrow-down';
    case ArchiveBoxXMark = 'archive-box-x-mark';

    // Products / Inventory
    case cube = 'cube';
    case CubeTransparent = 'cube-transparent';
    case ShoppingBag = 'shopping-bag';
    case ShoppingCart = 'shopping-cart';
    case Tag = 'tag';
    case Gift = 'gift';
    case Truck = 'truck';
    case BuildingStorefront = 'building-storefront';

    // Vehicles / Workshop
    case Wrench = 'wrench';
    case Cog6Tooth = 'cog-6-tooth';
    case Cog8Tooth = 'cog-8-tooth';
    case BuildingOffice = 'building-office';
    case HomeModern = 'home-modern';

    // People
    case User = 'user';
    case Users = 'users';
    case UserGroup = 'user-group';
    case UserPlus = 'user-plus';
    case UserMinus = 'user-minus';

    // Business
    case Briefcase = 'briefcase';
    case Banknotes = 'banknotes';
    case CreditCard = 'credit-card';
    case CurrencyDollar = 'currency-dollar';
    case ReceiptPercent = 'receipt-percent';
    case Calculator = 'calculator';

    // Communication
    case Phone = 'phone';
    case Envelope = 'envelope';
    case ChatBubbleLeft = 'chat-bubble-left';
    case Bell = 'bell';

    // Status
    case CheckCircle = 'check-circle';
    case XCircle = 'x-circle';
    case ExclamationCircle = 'exclamation-circle';
    case InformationCircle = 'information-circle';
    case QuestionMarkCircle = 'question-mark-circle';

    // Actions
    case Plus = 'plus';
    case Minus = 'minus';
    case Pencil = 'pencil';
    case Trash = 'trash';
    case Eye = 'eye';
    case EyeSlash = 'eye-slash';
    case ArrowDownTray = 'arrow-down-tray';
    case ArrowUpTray = 'arrow-up-tray';
    case ArrowPath = 'arrow-path';

    // Organization
    case Squares2x2 = 'squares-2x2';
    case SquaresPlus = 'squares-plus';
    case ListBullet = 'list-bullet';
    case QueueList = 'queue-list';
    case TableCells = 'table-cells';

    // Other useful
    case Star = 'star';
    case Heart = 'heart';
    case Bookmark = 'bookmark';
    case Flag = 'flag';
    case MapPin = 'map-pin';
    case Calendar = 'calendar';
    case Clock = 'clock';
    case Photo = 'photo';
    case PaperClip = 'paper-clip';
    case Link = 'link';
    case LockClosed = 'lock-closed';
    case ShieldCheck = 'shield-check';
    // Automotive / Mechanical
    case Cog = 'cog';
    case Settings = 'settings';
    case CircleDot = 'circle-dot';
    case Layers = 'layers';
    case Circle = 'circle';
    case CircleChevronDown = 'circle-chevron-down';

    case Fuel = 'fuel';
    case Droplet = 'droplet';
    case SprayCan = 'spray-can';
    case AdjustmentsHorizontal = 'adjustments-horizontal';

    case Fan = 'fan';
    case PanelTop = 'panel-top';
    case WavesHorizontal = 'waves-horizontal';
    case Thermometer = 'thermometer';

    case Zap = 'zap';
    case Battery = 'battery';
    case RefreshCw = 'refresh-cw';
    case Power = 'power';
    case Radio = 'radio';
    case SquareStack = 'square-stack';
    case Radar = 'radar';
    case Lightbulb = 'lightbulb';

    case Disc = 'disc';
    case MoveHorizontal = 'move-horizontal';
    case CircleStop = 'circle-stop';
    case Square = 'square';
    case Grip = 'grip';
    case Cylinder = 'cylinder';

    case Move = 'move';
    case ArrowDownUp = 'arrow-down-up';
    case GitBranch = 'git-branch';
    case GitMerge = 'git-merge';

    // Fluids / Consumables
    case Droplets = 'droplets';
    case Box = 'box';
    case Wind = 'wind';
    case AirVent = 'air-vent';
    case FlaskConical = 'flask-conical';
    case Paintbrush = 'paintbrush';

    // Tools
    case Hammer = 'hammer';
    case WrenchScrewdriver = 'wrench-screwdriver';
    case Drill = 'drill';

    // Vehicles
    case Car = 'car';
    case CarFront = 'car-front';
    case Warehouse = 'warehouse';
    case Armchair = 'armchair';
}
