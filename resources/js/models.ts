export const Availability = {
    NOT_AVAILABLE: 0,
    AVAILABLE: 1
};
export const DayOfWeek = {
    MONDAY: 1,
    TUESDAY: 2,
    WEDNESDAY: 3,
    THURSDAY: 4,
    FRIDAY: 5,
    SATURDAY: 6,
    SUNDAY: 7
};
export const Maturity = {
    NORMAL: 0,
    MATURE: 1
};
export const NightMode = {
    DISABLE: 0,
    ENABLE: 1
};
export const Removal = {
    DELETE: 0,
    AVAILABILITY: 1
};
export const Sex = {
    MALE: 1,
    FEMALE: 2,
    OTHER: 3
};
export const Verification = {
    NOT_VERIFIED: 0,
    VERIFIED: 1
};
export class GlobalSetting {
    id: number;
    name: string;
    category: string;
    value: string;
    value_binary: string;
    data_type: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.category = (obj_.category !== undefined) ? obj_.category : null;        this.value = (obj_.value !== undefined) ? obj_.value : null;        this.value_binary = (obj_.value_binary !== undefined) ? obj_.value_binary : null;        this.data_type = (obj_.data_type !== undefined) ? obj_.data_type : null;
    }
}
export const GlobalSettingFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Resource {
    id: number;
    title: string;
    description: string;
    extension: string;
    path: string;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;        this.description = (obj_.description !== undefined) ? obj_.description : null;        this.extension = (obj_.extension !== undefined) ? obj_.extension : null;        this.path = (obj_.path !== undefined) ? obj_.path : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const ResourceFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Language {
    id: number;
    code: string;
    name: string;
    original_name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.code = (obj_.code !== undefined) ? obj_.code : null;        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.original_name = (obj_.original_name !== undefined) ? obj_.original_name : null;
    }
}
export const LanguageFlags = {
    NORMAL: 0,
    ALL: 1
};
export class User {
    id: number;
    email: string;
    username: string;
    password: string;
    birthdate: Date;
    sex: number;
    creation_date: Date;
    status: string;
    profile_image: Resource;
    profile_background: Resource;
    about_me: string;
    verified: number;
    display_language: Language;
    email_communication_language: Language;
    translation_language: Language;
    night_mode: number;
    available: number;
    roles: Role;
    punishments: Punishment;
    logs: Log;
    purchases: AccountPurchase;
    tickets: Ticket;
    anime_status: any;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.email = (obj_.email !== undefined) ? obj_.email : null;        this.username = (obj_.username !== undefined) ? obj_.username : null;        this.password = (obj_.password !== undefined) ? obj_.password : null;        this.birthdate = (obj_.birthdate !== undefined) ? obj_.birthdate : null;        this.sex = (obj_.sex !== undefined) ? obj_.sex : null;        this.creation_date = (obj_.creation_date !== undefined) ? obj_.creation_date : null;        this.status = (obj_.status !== undefined) ? obj_.status : null;        this.profile_image = (obj_.profile_image !== undefined) ? obj_.profile_image : null;        this.profile_background = (obj_.profile_background !== undefined) ? obj_.profile_background : null;        this.about_me = (obj_.about_me !== undefined) ? obj_.about_me : null;        this.verified = (obj_.verified !== undefined) ? obj_.verified : null;        this.display_language = (obj_.display_language !== undefined) ? obj_.display_language : null;        this.email_communication_language = (obj_.email_communication_language !== undefined) ? obj_.email_communication_language : null;        this.translation_language = (obj_.translation_language !== undefined) ? obj_.translation_language : null;        this.night_mode = (obj_.night_mode !== undefined) ? obj_.night_mode : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;        this.roles = (obj_.roles !== undefined) ? obj_.roles : {};        this.punishments = (obj_.punishments !== undefined) ? obj_.punishments : {};        this.logs = (obj_.logs !== undefined) ? obj_.logs : {};        this.purchases = (obj_.purchases !== undefined) ? obj_.purchases : {};        this.tickets = (obj_.tickets !== undefined) ? obj_.tickets : {};        this.anime_status = (obj_.anime_status !== undefined) ? obj_.anime_status : {};
    }
}
export const UserFlags = {
    ROLES: 2,
    PUNISHMENTS: 3,
    LOGS: 4,
    PURCHASES: 5,
    TICKETS: 6,
    ANIME_STATUS: 7,
    NORMAL: 0,
    ALL: 1
};
export class SourceType {
    id: number;
    name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const SourceTypeFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Audience {
    id: number;
    name: string;
    minimum_age: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.minimum_age = (obj_.minimum_age !== undefined) ? obj_.minimum_age : null;
    }
}
export const AudienceFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Anime {
    id: number;
    title: string;
    original_title: string;
    synopsis: string;
    start_date: Date;
    end_date: Date;
    mature: number;
    launch_day: number;
    source: SourceType;
    audience: Audience;
    trailer: string;
    available: number;
    videos: Video;
    seasons: Season;
    genders: Gender;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;        this.original_title = (obj_.original_title !== undefined) ? obj_.original_title : null;        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;        this.start_date = (obj_.start_date !== undefined) ? obj_.start_date : null;        this.end_date = (obj_.end_date !== undefined) ? obj_.end_date : null;        this.mature = (obj_.mature !== undefined) ? obj_.mature : null;        this.launch_day = (obj_.launch_day !== undefined) ? obj_.launch_day : null;        this.source = (obj_.source !== undefined) ? obj_.source : null;        this.audience = (obj_.audience !== undefined) ? obj_.audience : null;        this.trailer = (obj_.trailer !== undefined) ? obj_.trailer : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;        this.videos = (obj_.videos !== undefined) ? obj_.videos : {};        this.seasons = (obj_.seasons !== undefined) ? obj_.seasons : {};        this.genders = (obj_.genders !== undefined) ? obj_.genders : {};
    }
}
export const AnimeFlags = {
    VIDEOS: 2,
    SEASONS: 3,
    GENDERS: 4,
    NORMAL: 0,
    ALL: 1
};
export class Season {
    id: number;
    numeration: number;
    name: string;
    synopsis: string;
    release_date: Date;
    available: number;
    videos: any;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.numeration = (obj_.numeration !== undefined) ? obj_.numeration : null;        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;        this.release_date = (obj_.release_date !== undefined) ? obj_.release_date : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;        this.videos = (obj_.videos !== undefined) ? obj_.videos : {};
    }
}
export const SeasonFlags = {
    VIDEOS: 2,
    NORMAL: 0,
    ALL: 1
};
export class VideoType {
    id: number;
    name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const VideoTypeFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Video {
    id: number;
    video_type: VideoType;
    numeration: number;
    title: string;
    synopsis: string;
    duration: number;
    opening_start: number;
    opening_end: number;
    ending_start: number;
    ending_end: number;
    path: string;
    available: number;
    anime: number;
    subtitles: any;
    dubbing: any;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.video_type = (obj_.video_type !== undefined) ? obj_.video_type : null;        this.numeration = (obj_.numeration !== undefined) ? obj_.numeration : null;        this.title = (obj_.title !== undefined) ? obj_.title : null;        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;        this.opening_start = (obj_.opening_start !== undefined) ? obj_.opening_start : null;        this.opening_end = (obj_.opening_end !== undefined) ? obj_.opening_end : null;        this.ending_start = (obj_.ending_start !== undefined) ? obj_.ending_start : null;        this.ending_end = (obj_.ending_end !== undefined) ? obj_.ending_end : null;        this.path = (obj_.path !== undefined) ? obj_.path : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;        this.anime = (obj_.anime !== undefined) ? obj_.anime : null;        this.subtitles = (obj_.subtitles !== undefined) ? obj_.subtitles : {};        this.dubbing = (obj_.dubbing !== undefined) ? obj_.dubbing : {};
    }
}
export const VideoFlags = {
    SUBTITLES: 2,
    DUBBING: 3,
    NORMAL: 0,
    ALL: 1
};
export class Subtitle {
    id: number;
    language: Language;
    path: string;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.language = (obj_.language !== undefined) ? obj_.language : null;        this.path = (obj_.path !== undefined) ? obj_.path : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const SubtitleFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Dubbing {
    id: number;
    language: Language;
    path: string;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.language = (obj_.language !== undefined) ? obj_.language : null;        this.path = (obj_.path !== undefined) ? obj_.path : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const DubbingFlags = {
    NORMAL: 0,
    ALL: 1
};
export class PunishmentType {
    id: number;
    name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const PunishmentTypeFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Punishment {
    id: number;
    punishment_type: PunishmentType;
    reason: string;
    lasts_until: Date;
    performed_by: User;
    performed_date: Date;
    revoked_by: User;
    revoked_date: Date;
    revoked_reason: string;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.punishment_type = (obj_.punishment_type !== undefined) ? obj_.punishment_type : null;        this.reason = (obj_.reason !== undefined) ? obj_.reason : null;        this.lasts_until = (obj_.lasts_until !== undefined) ? obj_.lasts_until : null;        this.performed_by = (obj_.performed_by !== undefined) ? obj_.performed_by : null;        this.performed_date = (obj_.performed_date !== undefined) ? obj_.performed_date : null;        this.revoked_by = (obj_.revoked_by !== undefined) ? obj_.revoked_by : null;        this.revoked_date = (obj_.revoked_date !== undefined) ? obj_.revoked_date : null;        this.revoked_reason = (obj_.revoked_reason !== undefined) ? obj_.revoked_reason : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const PunishmentFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Gender {
    id: number;
    name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const GenderFlags = {
    NORMAL: 0,
    ALL: 1
};
export class AnimeStatus {
    id: number;
    name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const AnimeStatusFlags = {
    NORMAL: 0,
    ALL: 1
};
export class TicketStatus {
    id: number;
    name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const TicketStatusFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Ticket {
    id: number;
    title: string;
    attended_by: User;
    status: TicketStatus;
    created_at: Date;
    closed_at: Date;
    closed_by: User;
    evaluation: number;
    messages: any;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;        this.attended_by = (obj_.attended_by !== undefined) ? obj_.attended_by : null;        this.status = (obj_.status !== undefined) ? obj_.status : null;        this.created_at = (obj_.created_at !== undefined) ? obj_.created_at : null;        this.closed_at = (obj_.closed_at !== undefined) ? obj_.closed_at : null;        this.closed_by = (obj_.closed_by !== undefined) ? obj_.closed_by : null;        this.evaluation = (obj_.evaluation !== undefined) ? obj_.evaluation : null;        this.messages = (obj_.messages !== undefined) ? obj_.messages : {};
    }
}
export const TicketFlags = {
    MESSAGES: 2,
    NORMAL: 0,
    ALL: 1
};
export class TicketMessage {
    id: number;
    author: User;
    content: string;
    sent_at: Date;
    attachments: any;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.author = (obj_.author !== undefined) ? obj_.author : null;        this.content = (obj_.content !== undefined) ? obj_.content : null;        this.sent_at = (obj_.sent_at !== undefined) ? obj_.sent_at : null;        this.attachments = (obj_.attachments !== undefined) ? obj_.attachments : {};
    }
}
export const TicketMessageFlags = {
    ATTACHMENTS: 2,
    NORMAL: 0,
    ALL: 1
};
export class Role {
    id: number;
    name: string;
    permissions: any;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.permissions = (obj_.permissions !== undefined) ? obj_.permissions : {};
    }
}
export const RoleFlags = {
    PERMISSIONS: 2,
    NORMAL: 0,
    ALL: 1
};
export class Permission {
    id: number;
    tag: string;
    name: string;
    description: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.tag = (obj_.tag !== undefined) ? obj_.tag : null;        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const PermissionFlags = {
    NORMAL: 0,
    ALL: 1
};
export class AccountPlan {
    id: number;
    name: string;
    duration: number;
    price: number;
    stack: number;
    maximum: number;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;        this.price = (obj_.price !== undefined) ? obj_.price : null;        this.stack = (obj_.stack !== undefined) ? obj_.stack : null;        this.maximum = (obj_.maximum !== undefined) ? obj_.maximum : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const AccountPlanFlags = {
    NORMAL: 0,
    ALL: 1
};
export class AccountPurchase {
    id: number;
    plan: AccountPlan;
    price: number;
    purchased_on: Date;
    duration: number;
    revoked_by: User;
    revoked_reason: string;
    revoked_at: Date;
    rescued_at: Date;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.plan = (obj_.plan !== undefined) ? obj_.plan : null;        this.price = (obj_.price !== undefined) ? obj_.price : null;        this.purchased_on = (obj_.purchased_on !== undefined) ? obj_.purchased_on : null;        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;        this.revoked_by = (obj_.revoked_by !== undefined) ? obj_.revoked_by : null;        this.revoked_reason = (obj_.revoked_reason !== undefined) ? obj_.revoked_reason : null;        this.revoked_at = (obj_.revoked_at !== undefined) ? obj_.revoked_at : null;        this.rescued_at = (obj_.rescued_at !== undefined) ? obj_.rescued_at : null;        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const AccountPurchaseFlags = {
    NORMAL: 0,
    ALL: 1
};
export class LogAction {
    id: number;
    name: string;
    description: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const LogActionFlags = {
    NORMAL: 0,
    ALL: 1
};
export class Log {
    id: number;
    action_type: LogAction;
    arguments: any;
    description: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.action_type = (obj_.action_type !== undefined) ? obj_.action_type : null;        this.arguments = (obj_.arguments !== undefined) ? obj_.arguments : {};        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const LogFlags = {
    NORMAL: 0,
    ALL: 1
};
