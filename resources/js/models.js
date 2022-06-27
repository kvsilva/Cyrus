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
export const AnimeStatus = {
    FAVOURITE: 1,
    LIKE: 2,
    DONT_LIKE: 3,
    WATCH_LATER: 4
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
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.category = (obj_.category !== undefined) ? obj_.category : null;
        this.value = (obj_.value !== undefined) ? obj_.value : null;
        this.value_binary = (obj_.value_binary !== undefined) ? obj_.value_binary : null;
        this.data_type = (obj_.data_type !== undefined) ? obj_.data_type : null;
    }
}
export const GlobalSettingFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Resource {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
        this.extension = (obj_.extension !== undefined) ? obj_.extension : null;
        this.path = (obj_.path !== undefined) ? obj_.path : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const ResourceFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Language {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.code = (obj_.code !== undefined) ? obj_.code : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.original_name = (obj_.original_name !== undefined) ? obj_.original_name : null;
    }
}
export const LanguageFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class User {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.email = (obj_.email !== undefined) ? obj_.email : null;
        this.username = (obj_.username !== undefined) ? obj_.username : null;
        this.password = (obj_.password !== undefined) ? obj_.password : null;
        this.birthdate = (obj_.birthdate !== undefined) ? obj_.birthdate : null;
        this.sex = (obj_.sex !== undefined) ? obj_.sex : null;
        this.creation_date = (obj_.creation_date !== undefined) ? obj_.creation_date : null;
        this.status = (obj_.status !== undefined) ? obj_.status : null;
        this.profile_image = (obj_.profile_image !== undefined) ? obj_.profile_image : null;
        this.profile_background = (obj_.profile_background !== undefined) ? obj_.profile_background : null;
        this.about_me = (obj_.about_me !== undefined) ? obj_.about_me : null;
        this.verified = (obj_.verified !== undefined) ? obj_.verified : null;
        this.display_language = (obj_.display_language !== undefined) ? obj_.display_language : null;
        this.email_communication_language = (obj_.email_communication_language !== undefined) ? obj_.email_communication_language : null;
        this.translation_language = (obj_.translation_language !== undefined) ? obj_.translation_language : null;
        this.night_mode = (obj_.night_mode !== undefined) ? obj_.night_mode : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.roles = (obj_.roles !== undefined) ? obj_.roles : [];
        this.punishments = (obj_.punishments !== undefined) ? obj_.punishments : [];
        this.logs = (obj_.logs !== undefined) ? obj_.logs : [];
        this.purchases = (obj_.purchases !== undefined) ? obj_.purchases : [];
        this.tickets = (obj_.tickets !== undefined) ? obj_.tickets : [];
        this.anime_history = (obj_.anime_history !== undefined) ? obj_.anime_history : [];
        this.video_history = (obj_.video_history !== undefined) ? obj_.video_history : [];
    }
}
export const UserFlags = {
    ROLES: { name: "ROLES", value: 2 },
    PUNISHMENTS: { name: "PUNISHMENTS", value: 3 },
    LOGS: { name: "LOGS", value: 4 },
    PURCHASES: { name: "PURCHASES", value: 5 },
    TICKETS: { name: "TICKETS", value: 6 },
    ANIMEHISTORY: { name: "ANIMEHISTORY", value: 7 },
    VIDEOHISTORY: { name: "VIDEOHISTORY", value: 8 },
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class SourceType {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const SourceTypeFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Audience {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.minimum_age = (obj_.minimum_age !== undefined) ? obj_.minimum_age : null;
    }
}
export const AudienceFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Anime {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.original_title = (obj_.original_title !== undefined) ? obj_.original_title : null;
        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;
        this.profile = (obj_.profile !== undefined) ? obj_.profile : null;
        this.cape = (obj_.cape !== undefined) ? obj_.cape : null;
        this.start_date = (obj_.start_date !== undefined) ? obj_.start_date : null;
        this.end_date = (obj_.end_date !== undefined) ? obj_.end_date : null;
        this.mature = (obj_.mature !== undefined) ? obj_.mature : null;
        this.launch_day = (obj_.launch_day !== undefined) ? obj_.launch_day : null;
        this.launch_time = (obj_.launch_time !== undefined) ? obj_.launch_time : null;
        this.source = (obj_.source !== undefined) ? obj_.source : null;
        this.audience = (obj_.audience !== undefined) ? obj_.audience : null;
        this.trailer = (obj_.trailer !== undefined) ? obj_.trailer : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.videos = (obj_.videos !== undefined) ? obj_.videos : [];
        this.seasons = (obj_.seasons !== undefined) ? obj_.seasons : [];
        this.genders = (obj_.genders !== undefined) ? obj_.genders : [];
    }
}
export const AnimeFlags = {
    VIDEOS: { name: "VIDEOS", value: 2 },
    SEASONS: { name: "SEASONS", value: 3 },
    GENDERS: { name: "GENDERS", value: 4 },
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Season {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.numeration = (obj_.numeration !== undefined) ? obj_.numeration : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;
        this.release_date = (obj_.release_date !== undefined) ? obj_.release_date : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.videos = (obj_.videos !== undefined) ? obj_.videos : [];
    }
}
export const SeasonFlags = {
    VIDEOS: { name: "VIDEOS", value: 2 },
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class VideoType {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const VideoTypeFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Video {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.video_type = (obj_.video_type !== undefined) ? obj_.video_type : null;
        this.numeration = (obj_.numeration !== undefined) ? obj_.numeration : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;
        this.thumbnail = (obj_.thumbnail !== undefined) ? obj_.thumbnail : null;
        this.release_date = (obj_.release_date !== undefined) ? obj_.release_date : null;
        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;
        this.opening_start = (obj_.opening_start !== undefined) ? obj_.opening_start : null;
        this.opening_end = (obj_.opening_end !== undefined) ? obj_.opening_end : null;
        this.ending_start = (obj_.ending_start !== undefined) ? obj_.ending_start : null;
        this.ending_end = (obj_.ending_end !== undefined) ? obj_.ending_end : null;
        this.path = (obj_.path !== undefined) ? obj_.path : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.anime = (obj_.anime !== undefined) ? obj_.anime : null;
        this.season = (obj_.season !== undefined) ? obj_.season : null;
        this.subtitles = (obj_.subtitles !== undefined) ? obj_.subtitles : [];
        this.dubbing = (obj_.dubbing !== undefined) ? obj_.dubbing : [];
    }
}
export const VideoFlags = {
    SUBTITLES: { name: "SUBTITLES", value: 2 },
    DUBBING: { name: "DUBBING", value: 3 },
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Subtitle {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.language = (obj_.language !== undefined) ? obj_.language : null;
        this.path = (obj_.path !== undefined) ? obj_.path : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const SubtitleFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Dubbing {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.language = (obj_.language !== undefined) ? obj_.language : null;
        this.path = (obj_.path !== undefined) ? obj_.path : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const DubbingFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class PunishmentType {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const PunishmentTypeFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Punishment {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.punishment_type = (obj_.punishment_type !== undefined) ? obj_.punishment_type : null;
        this.reason = (obj_.reason !== undefined) ? obj_.reason : null;
        this.lasts_until = (obj_.lasts_until !== undefined) ? obj_.lasts_until : null;
        this.performed_by = (obj_.performed_by !== undefined) ? obj_.performed_by : null;
        this.performed_date = (obj_.performed_date !== undefined) ? obj_.performed_date : null;
        this.revoked_by = (obj_.revoked_by !== undefined) ? obj_.revoked_by : null;
        this.revoked_date = (obj_.revoked_date !== undefined) ? obj_.revoked_date : null;
        this.revoked_reason = (obj_.revoked_reason !== undefined) ? obj_.revoked_reason : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const PunishmentFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Gender {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const GenderFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class TicketStatus {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
    }
}
export const TicketStatusFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Ticket {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.attended_by = (obj_.attended_by !== undefined) ? obj_.attended_by : null;
        this.status = (obj_.status !== undefined) ? obj_.status : null;
        this.created_at = (obj_.created_at !== undefined) ? obj_.created_at : null;
        this.closed_at = (obj_.closed_at !== undefined) ? obj_.closed_at : null;
        this.closed_by = (obj_.closed_by !== undefined) ? obj_.closed_by : null;
        this.evaluation = (obj_.evaluation !== undefined) ? obj_.evaluation : null;
        this.messages = (obj_.messages !== undefined) ? obj_.messages : [];
    }
}
export const TicketFlags = {
    MESSAGES: { name: "MESSAGES", value: 2 },
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class TicketMessage {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.author = (obj_.author !== undefined) ? obj_.author : null;
        this.content = (obj_.content !== undefined) ? obj_.content : null;
        this.sent_at = (obj_.sent_at !== undefined) ? obj_.sent_at : null;
        this.attachments = (obj_.attachments !== undefined) ? obj_.attachments : [];
    }
}
export const TicketMessageFlags = {
    ATTACHMENTS: { name: "ATTACHMENTS", value: 2 },
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Role {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.permissions = (obj_.permissions !== undefined) ? obj_.permissions : [];
    }
}
export const RoleFlags = {
    PERMISSIONS: { name: "PERMISSIONS", value: 2 },
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Permission {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.tag = (obj_.tag !== undefined) ? obj_.tag : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const PermissionFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class AccountPlan {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;
        this.price = (obj_.price !== undefined) ? obj_.price : null;
        this.stack = (obj_.stack !== undefined) ? obj_.stack : null;
        this.maximum = (obj_.maximum !== undefined) ? obj_.maximum : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const AccountPlanFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class AccountPurchase {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.plan = (obj_.plan !== undefined) ? obj_.plan : null;
        this.price = (obj_.price !== undefined) ? obj_.price : null;
        this.purchased_on = (obj_.purchased_on !== undefined) ? obj_.purchased_on : null;
        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;
        this.revoked_by = (obj_.revoked_by !== undefined) ? obj_.revoked_by : null;
        this.revoked_reason = (obj_.revoked_reason !== undefined) ? obj_.revoked_reason : null;
        this.revoked_at = (obj_.revoked_at !== undefined) ? obj_.revoked_at : null;
        this.rescued_at = (obj_.rescued_at !== undefined) ? obj_.rescued_at : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const AccountPurchaseFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class LogAction {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const LogActionFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Log {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.action_type = (obj_.action_type !== undefined) ? obj_.action_type : null;
        this.arguments = (obj_.arguments !== undefined) ? obj_.arguments : [];
        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const LogFlags = {
    NORMAL: { name: "NORMAL", value: 0 },
    ALL: { name: "ALL", value: 1 }
};
export class Paginator {
    constructor(obj) {
        const obj_ = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.page = (obj_.page !== undefined) ? obj_.page : null;
        this.items = (obj_.items !== undefined) ? obj_.items : null;
        this.totalItems = (obj_.totalItems !== undefined) ? obj_.totalItems : null;
        this.totalPages = (obj_.totalPages !== undefined) ? obj_.totalPages : null;
    }
}
export const models = {
    "GlobalSetting": GlobalSetting,
    "Resource": Resource,
    "Language": Language,
    "User": User,
    "SourceType": SourceType,
    "Audience": Audience,
    "Anime": Anime,
    "Season": Season,
    "VideoType": VideoType,
    "Video": Video,
    "Subtitle": Subtitle,
    "Dubbing": Dubbing,
    "PunishmentType": PunishmentType,
    "Punishment": Punishment,
    "Gender": Gender,
    "TicketStatus": TicketStatus,
    "Ticket": Ticket,
    "TicketMessage": TicketMessage,
    "Role": Role,
    "Permission": Permission,
    "AccountPlan": AccountPlan,
    "AccountPurchase": AccountPurchase,
    "LogAction": LogAction,
    "Log": Log,
    "Paginator": Paginator
};
