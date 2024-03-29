export declare const Availability: {
    NOT_AVAILABLE: {
        name: string;
        value: number;
    };
    AVAILABLE: {
        name: string;
        value: number;
    };
    BOTH: {
        name: string;
        value: number;
    };
};
export declare const DayOfWeek: {
    MONDAY: {
        name: string;
        value: number;
    };
    TUESDAY: {
        name: string;
        value: number;
    };
    WEDNESDAY: {
        name: string;
        value: number;
    };
    THURSDAY: {
        name: string;
        value: number;
    };
    FRIDAY: {
        name: string;
        value: number;
    };
    SATURDAY: {
        name: string;
        value: number;
    };
    SUNDAY: {
        name: string;
        value: number;
    };
};
export declare const AnimeStatus: {
    FAVOURITE: {
        name: string;
        value: number;
    };
    LIKE: {
        name: string;
        value: number;
    };
    DONT_LIKE: {
        name: string;
        value: number;
    };
    WATCH_LATER: {
        name: string;
        value: number;
    };
};
export declare const TicketStatus: {
    OPEN: {
        name: string;
        value: number;
    };
    CLOSED: {
        name: string;
        value: number;
    };
    AWAITING_YOUR_RESPONSE: {
        name: string;
        value: number;
    };
};
export declare const Maturity: {
    NORMAL: {
        name: string;
        value: number;
    };
    MATURE: {
        name: string;
        value: number;
    };
};
export declare const Removal: {
    DELETE: {
        name: string;
        value: number;
    };
    AVAILABILITY: {
        name: string;
        value: number;
    };
};
export declare const Sex: {
    MALE: {
        name: string;
        value: number;
    };
    FEMALE: {
        name: string;
        value: number;
    };
    OTHER: {
        name: string;
        value: number;
    };
};
export declare const Verification: {
    NOT_VERIFIED: {
        name: string;
        value: number;
    };
    VERIFIED: {
        name: string;
        value: number;
    };
};
export declare class GlobalSetting {
    id: number;
    name: string;
    category: string;
    value: string;
    value_binary: string;
    data_type: string;
    constructor(obj?: any);
}
export declare const GlobalSettingFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Resource {
    id: number;
    original_name: string;
    extension: string;
    path: string;
    available: number;
    constructor(obj?: any);
}
export declare const ResourceFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Language {
    id: number;
    code: string;
    name: string;
    original_name: string;
    constructor(obj?: any);
}
export declare const LanguageFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class User {
    id: number;
    email: string;
    username: string;
    password: string;
    birthdate: Date;
    sex: number;
    creation_date: Date;
    status: string;
    profile_image: Resource | null;
    profile_background: Resource | null;
    about_me: string;
    verified: number;
    display_language: Language | null;
    email_communication_language: Language | null;
    translation_language: Language | null;
    available: number;
    roles: Role[];
    punishments: Punishment[];
    logs: Log[];
    purchases: AccountPurchase[];
    tickets: Ticket[];
    anime_history: any[];
    video_history: any[];
    constructor(obj?: any);
}
export declare const UserFlags: {
    ROLES: {
        name: string;
        value: number;
    };
    PUNISHMENTS: {
        name: string;
        value: number;
    };
    LOGS: {
        name: string;
        value: number;
    };
    PURCHASES: {
        name: string;
        value: number;
    };
    TICKETS: {
        name: string;
        value: number;
    };
    ANIMEHISTORY: {
        name: string;
        value: number;
    };
    VIDEOHISTORY: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class SourceType {
    id: number;
    name: string;
    constructor(obj?: any);
}
export declare const SourceTypeFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Audience {
    id: number;
    name: string;
    minimum_age: number;
    constructor(obj?: any);
}
export declare const AudienceFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Anime {
    id: number;
    title: string;
    original_title: string;
    synopsis: string;
    profile: Resource | null;
    cape: Resource | null;
    start_date: Date;
    end_date: Date;
    mature: number;
    launch_day: number;
    launch_time: Date;
    source: SourceType | null;
    audience: Audience | null;
    trailer: string;
    available: number;
    videos: Video[];
    seasons: Season[];
    genders: Gender[];
    comments: CommentAnime[];
    constructor(obj?: any);
}
export declare const AnimeFlags: {
    VIDEOS: {
        name: string;
        value: number;
    };
    SEASONS: {
        name: string;
        value: number;
    };
    GENDERS: {
        name: string;
        value: number;
    };
    COMMENTANIMES: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Season {
    id: number;
    numeration: number;
    name: string;
    synopsis: string;
    release_date: Date;
    end_date: Date;
    available: number;
    anime: number;
    videos: Video[];
    constructor(obj?: any);
}
export declare const SeasonFlags: {
    VIDEOS: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class VideoType {
    id: number;
    name: string;
    constructor(obj?: any);
}
export declare const VideoTypeFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Video {
    id: number;
    video_type: VideoType | null;
    numeration: number;
    title: string;
    synopsis: string;
    thumbnail: Resource | null;
    release_date: Date;
    duration: number;
    opening_start: number;
    opening_end: number;
    ending_start: number;
    ending_end: number;
    path: Resource | null;
    available: number;
    anime: Anime | null;
    season: Season | null;
    subtitles: Subtitle[];
    comments: CommentVideo[];
    dubbing: any[];
    constructor(obj?: any);
}
export declare const VideoFlags: {
    SUBTITLES: {
        name: string;
        value: number;
    };
    DUBBING: {
        name: string;
        value: number;
    };
    COMMENTVIDEOS: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Subtitle {
    id: number;
    language: Language | null;
    path: Resource | null;
    available: number;
    constructor(obj?: any);
}
export declare const SubtitleFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Dubbing {
    id: number;
    language: Language | null;
    path: string;
    available: number;
    constructor(obj?: any);
}
export declare const DubbingFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class PunishmentType {
    id: number;
    name: string;
    constructor(obj?: any);
}
export declare const PunishmentTypeFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Punishment {
    id: number;
    punishment_type: PunishmentType | null;
    reason: string;
    lasts_until: Date;
    performed_by: User | null;
    performed_date: Date;
    revoked_by: User | null;
    revoked_date: Date;
    revoked_reason: string;
    available: number;
    constructor(obj?: any);
}
export declare const PunishmentFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Gender {
    id: number;
    name: string;
    constructor(obj?: any);
}
export declare const GenderFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Ticket {
    id: number;
    subject: string;
    status: number;
    responsible: User | null;
    created_at: Date;
    closed_at: Date;
    closed_by: User | null;
    evaluation: number;
    user: User | null;
    messages: TicketMessage[];
    constructor(obj?: any);
}
export declare const TicketFlags: {
    TICKETMESSAGES: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class TicketMessage {
    id: number;
    author: User | null;
    content: string;
    sent_at: Date;
    ticket: Ticket | null;
    attachments: Resource[];
    constructor(obj?: any);
}
export declare const TicketMessageFlags: {
    TICKETMESSAGEATTACHMENTS: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Role {
    id: number;
    name: string;
    permissions: Permission[];
    constructor(obj?: any);
}
export declare const RoleFlags: {
    PERMISSIONS: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Permission {
    id: number;
    tag: string;
    name: string;
    description: string;
    constructor(obj?: any);
}
export declare const PermissionFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class AccountPlan {
    id: number;
    name: string;
    duration: number;
    price: number;
    stack: number;
    maximum: number;
    available: number;
    constructor(obj?: any);
}
export declare const AccountPlanFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class AccountPurchase {
    id: number;
    plan: AccountPlan | null;
    price: number;
    purchased_on: Date;
    duration: number;
    revoked_by: User | null;
    revoked_reason: string;
    revoked_at: Date;
    rescued_at: Date;
    available: number;
    constructor(obj?: any);
}
export declare const AccountPurchaseFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class LogAction {
    id: number;
    name: string;
    description: string;
    constructor(obj?: any);
}
export declare const LogActionFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class Log {
    id: number;
    action_type: LogAction | null;
    arguments: any[];
    description: string;
    constructor(obj?: any);
}
export declare const LogFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class News {
    id: number;
    created_at: Date;
    user: User | null;
    spotlight: boolean;
    available: number;
    editions: NewsBody[];
    comments: CommentNews[];
    constructor(obj?: any);
}
export declare const NewsFlags: {
    NEWSBODY: {
        name: string;
        value: number;
    };
    COMMENTNEWS: {
        name: string;
        value: number;
    };
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class NewsBody {
    id: number;
    edited_at: Date;
    content: string;
    title: string;
    subtitle: string;
    thumbnail: Resource | null;
    user: User | null;
    news: News | null;
    editions: NewsBody[];
    constructor(obj?: any);
}
export declare const NewsBodyFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class CommentAnime {
    id: number;
    post_date: Date;
    title: string;
    description: string;
    spoiler: boolean;
    classification: number;
    anime: Anime | null;
    user: User | null;
    constructor(obj?: any);
}
export declare const CommentAnimeFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class CommentVideo {
    id: number;
    post_date: Date;
    description: string;
    spoiler: boolean;
    video: Video | null;
    user: User | null;
    constructor(obj?: any);
}
export declare const CommentVideoFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class CommentNews {
    id: number;
    post_date: Date;
    description: string;
    user: User | null;
    news: News | null;
    constructor(obj?: any);
}
export declare const CommentNewsFlags: {
    NORMAL: {
        name: string;
        value: number;
    };
    ALL: {
        name: string;
        value: number;
    };
};
export declare class APIWebFile {
    id: number;
    name: string;
    type: string;
    size: number;
    tmp_name: string;
    error: number;
    full_path: string;
    extension: string;
    system_path: string;
    web_path: string;
    constructor(obj?: any);
}
export declare const flags: any;
export declare const models: any;
