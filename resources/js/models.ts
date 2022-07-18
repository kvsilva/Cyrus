export const Availability = {
    NOT_AVAILABLE: {name: "Indisponível", value: 0},
    AVAILABLE: {name: "Disponível", value: 1},
    BOTH: {name: "Ambos", value: 2}
};
export const DayOfWeek = {
    MONDAY: {name: "Segunda", value: 1},
    TUESDAY: {name: "Terça", value: 2},
    WEDNESDAY: {name: "Quarta", value: 3},
    THURSDAY: {name: "Quinta", value: 4},
    FRIDAY: {name: "Sexta", value: 5},
    SATURDAY: {name: "Sábado", value: 6},
    SUNDAY: {name: "Domingo", value: 7}
};
export const AnimeStatus = {
    FAVOURITE: {name: "Favorito", value: 1},
    LIKE: {name: "Gosto", value: 2},
    DONT_LIKE: {name: "Não Gosto", value: 3},
    WATCH_LATER: {name: "Ver mais tarde", value: 4}
};
export const TicketStatus = {
    OPEN: {name: "Aberto", value: 1},
    CLOSED: {name: "Fechado", value: 2},
    AWAITING_YOUR_RESPONSE: {name: "Aguardando a tua resposta", value: 3}
};
export const Maturity = {
    NORMAL: {name: "Normal", value: 0},
    MATURE: {name: "Mature", value: 1}
};
export const Removal = {
    DELETE: {name: "Delete", value: 0},
    AVAILABILITY: {name: "Availability", value: 1}
};
export const Sex = {
    MALE: {name: "Masculino", value: 1},
    FEMALE: {name: "Feminino", value: 2},
    OTHER: {name: "Outro", value: 3}
};
export const Verification = {
    NOT_VERIFIED: {name: "Não Verificado", value: 0},
    VERIFIED: {name: "Verificado", value: 1}
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
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.category = (obj_.category !== undefined) ? obj_.category : null;
        this.value = (obj_.value !== undefined) ? obj_.value : null;
        this.value_binary = (obj_.value_binary !== undefined) ? obj_.value_binary : null;
        this.data_type = (obj_.data_type !== undefined) ? obj_.data_type : null;
    }
}
export const GlobalSettingFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Resource {
    id: number;
    original_name: string;
    extension: string;
    path: string;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.original_name = (obj_.original_name !== undefined) ? obj_.original_name : null;
        this.extension = (obj_.extension !== undefined) ? obj_.extension : null;
        this.path = (obj_.path !== undefined) ? obj_.path : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const ResourceFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Language {
    id: number;
    code: string;
    name: string;
    original_name: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.code = (obj_.code !== undefined) ? obj_.code : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.original_name = (obj_.original_name !== undefined) ? obj_.original_name : null;
    }
}
export const LanguageFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
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

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.email = (obj_.email !== undefined) ? obj_.email : null;
        this.username = (obj_.username !== undefined) ? obj_.username : null;
        this.password = (obj_.password !== undefined) ? obj_.password : null;
        this.birthdate = (obj_.birthdate !== undefined) ? obj_.birthdate : null;
        this.sex = (obj_.sex !== undefined) ? obj_.sex : null;
        this.creation_date = (obj_.creation_date !== undefined) ? obj_.creation_date : null;
        this.status = (obj_.status !== undefined) ? obj_.status : null;
        this.profile_image = (obj_.profile_image !== undefined) ? (obj_.profile_image !== null ? new Resource(obj_.profile_image): null) : null;
        this.profile_background = (obj_.profile_background !== undefined) ? (obj_.profile_background !== null ? new Resource(obj_.profile_background): null) : null;
        this.about_me = (obj_.about_me !== undefined) ? obj_.about_me : null;
        this.verified = (obj_.verified !== undefined) ? obj_.verified : null;
        this.display_language = (obj_.display_language !== undefined) ? (obj_.display_language !== null ? new Language(obj_.display_language): null) : null;
        this.email_communication_language = (obj_.email_communication_language !== undefined) ? (obj_.email_communication_language !== null ? new Language(obj_.email_communication_language): null) : null;
        this.translation_language = (obj_.translation_language !== undefined) ? (obj_.translation_language !== null ? new Language(obj_.translation_language): null) : null;
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
    ROLES: {name: "ROLES", value: 2},
    PUNISHMENTS: {name: "PUNISHMENTS", value: 3},
    LOGS: {name: "LOGS", value: 4},
    PURCHASES: {name: "PURCHASES", value: 5},
    TICKETS: {name: "TICKETS", value: 6},
    ANIMEHISTORY: {name: "ANIMEHISTORY", value: 7},
    VIDEOHISTORY: {name: "VIDEOHISTORY", value: 8},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
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
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Audience {
    id: number;
    name: string;
    minimum_age: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.minimum_age = (obj_.minimum_age !== undefined) ? obj_.minimum_age : null;
    }
}
export const AudienceFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Anime {
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

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.original_title = (obj_.original_title !== undefined) ? obj_.original_title : null;
        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;
        this.profile = (obj_.profile !== undefined) ? (obj_.profile !== null ? new Resource(obj_.profile): null) : null;
        this.cape = (obj_.cape !== undefined) ? (obj_.cape !== null ? new Resource(obj_.cape): null) : null;
        this.start_date = (obj_.start_date !== undefined) ? obj_.start_date : null;
        this.end_date = (obj_.end_date !== undefined) ? obj_.end_date : null;
        this.mature = (obj_.mature !== undefined) ? obj_.mature : null;
        this.launch_day = (obj_.launch_day !== undefined) ? obj_.launch_day : null;
        this.launch_time = (obj_.launch_time !== undefined) ? obj_.launch_time : null;
        this.source = (obj_.source !== undefined) ? (obj_.source !== null ? new SourceType(obj_.source): null) : null;
        this.audience = (obj_.audience !== undefined) ? (obj_.audience !== null ? new Audience(obj_.audience): null) : null;
        this.trailer = (obj_.trailer !== undefined) ? obj_.trailer : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.videos = (obj_.videos !== undefined) ? obj_.videos : [];
        this.seasons = (obj_.seasons !== undefined) ? obj_.seasons : [];
        this.genders = (obj_.genders !== undefined) ? obj_.genders : [];
        this.comments = (obj_.comments !== undefined) ? obj_.comments : [];
    }
}
export const AnimeFlags = {
    VIDEOS: {name: "VIDEOS", value: 2},
    SEASONS: {name: "SEASONS", value: 3},
    GENDERS: {name: "GENDERS", value: 4},
    COMMENTANIMES: {name: "COMMENTANIMES", value: 5},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Season {
    id: number;
    numeration: number;
    name: string;
    synopsis: string;
    release_date: Date;
    end_date: Date;
    available: number;
    anime: number;
    videos: Video[];

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.numeration = (obj_.numeration !== undefined) ? obj_.numeration : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;
        this.release_date = (obj_.release_date !== undefined) ? obj_.release_date : null;
        this.end_date = (obj_.end_date !== undefined) ? obj_.end_date : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.anime = (obj_.anime !== undefined) ? obj_.anime : null;
        this.videos = (obj_.videos !== undefined) ? obj_.videos : [];
    }
}
export const SeasonFlags = {
    VIDEOS: {name: "VIDEOS", value: 2},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
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
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Video {
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

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.video_type = (obj_.video_type !== undefined) ? (obj_.video_type !== null ? new VideoType(obj_.video_type): null) : null;
        this.numeration = (obj_.numeration !== undefined) ? obj_.numeration : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.synopsis = (obj_.synopsis !== undefined) ? obj_.synopsis : null;
        this.thumbnail = (obj_.thumbnail !== undefined) ? (obj_.thumbnail !== null ? new Resource(obj_.thumbnail): null) : null;
        this.release_date = (obj_.release_date !== undefined) ? obj_.release_date : null;
        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;
        this.opening_start = (obj_.opening_start !== undefined) ? obj_.opening_start : null;
        this.opening_end = (obj_.opening_end !== undefined) ? obj_.opening_end : null;
        this.ending_start = (obj_.ending_start !== undefined) ? obj_.ending_start : null;
        this.ending_end = (obj_.ending_end !== undefined) ? obj_.ending_end : null;
        this.path = (obj_.path !== undefined) ? (obj_.path !== null ? new Resource(obj_.path): null) : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.anime = (obj_.anime !== undefined) ? (obj_.anime !== null ? new Anime(obj_.anime): null) : null;
        this.season = (obj_.season !== undefined) ? (obj_.season !== null ? new Season(obj_.season): null) : null;
        this.subtitles = (obj_.subtitles !== undefined) ? obj_.subtitles : [];
        this.comments = (obj_.comments !== undefined) ? obj_.comments : [];
        this.dubbing = (obj_.dubbing !== undefined) ? obj_.dubbing : [];
    }
}
export const VideoFlags = {
    SUBTITLES: {name: "SUBTITLES", value: 2},
    DUBBING: {name: "DUBBING", value: 3},
    COMMENTVIDEOS: {name: "COMMENTVIDEOS", value: 3},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Subtitle {
    id: number;
    language: Language | null;
    path: Resource | null;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.language = (obj_.language !== undefined) ? (obj_.language !== null ? new Language(obj_.language): null) : null;
        this.path = (obj_.path !== undefined) ? (obj_.path !== null ? new Resource(obj_.path): null) : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const SubtitleFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Dubbing {
    id: number;
    language: Language | null;
    path: string;
    available: number;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.language = (obj_.language !== undefined) ? (obj_.language !== null ? new Language(obj_.language): null) : null;
        this.path = (obj_.path !== undefined) ? obj_.path : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const DubbingFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
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
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Punishment {
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

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.punishment_type = (obj_.punishment_type !== undefined) ? (obj_.punishment_type !== null ? new PunishmentType(obj_.punishment_type): null) : null;
        this.reason = (obj_.reason !== undefined) ? obj_.reason : null;
        this.lasts_until = (obj_.lasts_until !== undefined) ? obj_.lasts_until : null;
        this.performed_by = (obj_.performed_by !== undefined) ? (obj_.performed_by !== null ? new User(obj_.performed_by): null) : null;
        this.performed_date = (obj_.performed_date !== undefined) ? obj_.performed_date : null;
        this.revoked_by = (obj_.revoked_by !== undefined) ? (obj_.revoked_by !== null ? new User(obj_.revoked_by): null) : null;
        this.revoked_date = (obj_.revoked_date !== undefined) ? obj_.revoked_date : null;
        this.revoked_reason = (obj_.revoked_reason !== undefined) ? obj_.revoked_reason : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const PunishmentFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
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
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Ticket {
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

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.subject = (obj_.subject !== undefined) ? obj_.subject : null;
        this.status = (obj_.status !== undefined) ? obj_.status : null;
        this.responsible = (obj_.responsible !== undefined) ? (obj_.responsible !== null ? new User(obj_.responsible): null) : null;
        this.created_at = (obj_.created_at !== undefined) ? obj_.created_at : null;
        this.closed_at = (obj_.closed_at !== undefined) ? obj_.closed_at : null;
        this.closed_by = (obj_.closed_by !== undefined) ? (obj_.closed_by !== null ? new User(obj_.closed_by): null) : null;
        this.evaluation = (obj_.evaluation !== undefined) ? obj_.evaluation : null;
        this.user = (obj_.user !== undefined) ? (obj_.user !== null ? new User(obj_.user): null) : null;
        this.messages = (obj_.messages !== undefined) ? obj_.messages : [];
    }
}
export const TicketFlags = {
    TICKETMESSAGES: {name: "TICKETMESSAGES", value: 2},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class TicketMessage {
    id: number;
    author: User | null;
    content: string;
    sent_at: Date;
    ticket: Ticket | null;
    attachments: Resource[];

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.author = (obj_.author !== undefined) ? (obj_.author !== null ? new User(obj_.author): null) : null;
        this.content = (obj_.content !== undefined) ? obj_.content : null;
        this.sent_at = (obj_.sent_at !== undefined) ? obj_.sent_at : null;
        this.ticket = (obj_.ticket !== undefined) ? (obj_.ticket !== null ? new Ticket(obj_.ticket): null) : null;
        this.attachments = (obj_.attachments !== undefined) ? obj_.attachments : [];
    }
}
export const TicketMessageFlags = {
    TICKETMESSAGEATTACHMENTS: {name: "TICKETMESSAGEATTACHMENTS", value: 2},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Role {
    id: number;
    name: string;
    permissions: Permission[];

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.permissions = (obj_.permissions !== undefined) ? obj_.permissions : [];
    }
}
export const RoleFlags = {
    PERMISSIONS: {name: "PERMISSIONS", value: 2},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Permission {
    id: number;
    tag: string;
    name: string;
    description: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.tag = (obj_.tag !== undefined) ? obj_.tag : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const PermissionFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
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
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;
        this.price = (obj_.price !== undefined) ? obj_.price : null;
        this.stack = (obj_.stack !== undefined) ? obj_.stack : null;
        this.maximum = (obj_.maximum !== undefined) ? obj_.maximum : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const AccountPlanFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class AccountPurchase {
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

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.plan = (obj_.plan !== undefined) ? (obj_.plan !== null ? new AccountPlan(obj_.plan): null) : null;
        this.price = (obj_.price !== undefined) ? obj_.price : null;
        this.purchased_on = (obj_.purchased_on !== undefined) ? obj_.purchased_on : null;
        this.duration = (obj_.duration !== undefined) ? obj_.duration : null;
        this.revoked_by = (obj_.revoked_by !== undefined) ? (obj_.revoked_by !== null ? new User(obj_.revoked_by): null) : null;
        this.revoked_reason = (obj_.revoked_reason !== undefined) ? obj_.revoked_reason : null;
        this.revoked_at = (obj_.revoked_at !== undefined) ? obj_.revoked_at : null;
        this.rescued_at = (obj_.rescued_at !== undefined) ? obj_.rescued_at : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
    }
}
export const AccountPurchaseFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class LogAction {
    id: number;
    name: string;
    description: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const LogActionFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class Log {
    id: number;
    action_type: LogAction | null;
    arguments: any[];
    description: string;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.action_type = (obj_.action_type !== undefined) ? (obj_.action_type !== null ? new LogAction(obj_.action_type): null) : null;
        this.arguments = (obj_.arguments !== undefined) ? obj_.arguments : [];
        this.description = (obj_.description !== undefined) ? obj_.description : null;
    }
}
export const LogFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class News {
    id: number;
    created_at: Date;
    user: User | null;
    spotlight: boolean;
    available: number;
    editions: NewsBody[];
    comments: CommentNews[];

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.created_at = (obj_.created_at !== undefined) ? obj_.created_at : null;
        this.user = (obj_.user !== undefined) ? (obj_.user !== null ? new User(obj_.user): null) : null;
        this.spotlight = (obj_.spotlight !== undefined) ? obj_.spotlight : null;
        this.available = (obj_.available !== undefined) ? obj_.available : null;
        this.editions = (obj_.editions !== undefined) ? obj_.editions : [];
        this.comments = (obj_.comments !== undefined) ? obj_.comments : [];
    }
}
export const NewsFlags = {
    NEWSBODY: {name: "NEWSBODY", value: 2},
    COMMENTNEWS: {name: "COMMENTNEWS", value: 3},
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class NewsBody {
    id: number;
    edited_at: Date;
    content: string;
    title: string;
    subtitle: string;
    thumbnail: Resource | null;
    user: User | null;
    news: News | null;
    editions: NewsBody[];

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.edited_at = (obj_.edited_at !== undefined) ? obj_.edited_at : null;
        this.content = (obj_.content !== undefined) ? obj_.content : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.subtitle = (obj_.subtitle !== undefined) ? obj_.subtitle : null;
        this.thumbnail = (obj_.thumbnail !== undefined) ? (obj_.thumbnail !== null ? new Resource(obj_.thumbnail): null) : null;
        this.user = (obj_.user !== undefined) ? (obj_.user !== null ? new User(obj_.user): null) : null;
        this.news = (obj_.news !== undefined) ? (obj_.news !== null ? new News(obj_.news): null) : null;
        this.editions = (obj_.editions !== undefined) ? obj_.editions : [];
    }
}
export const NewsBodyFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class CommentAnime {
    id: number;
    post_date: Date;
    title: string;
    description: string;
    spoiler: boolean;
    classification: number;
    anime: Anime | null;
    user: User | null;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.post_date = (obj_.post_date !== undefined) ? obj_.post_date : null;
        this.title = (obj_.title !== undefined) ? obj_.title : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
        this.spoiler = (obj_.spoiler !== undefined) ? obj_.spoiler : null;
        this.classification = (obj_.classification !== undefined) ? obj_.classification : null;
        this.anime = (obj_.anime !== undefined) ? (obj_.anime !== null ? new Anime(obj_.anime): null) : null;
        this.user = (obj_.user !== undefined) ? (obj_.user !== null ? new User(obj_.user): null) : null;
    }
}
export const CommentAnimeFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class CommentVideo {
    id: number;
    post_date: Date;
    description: string;
    spoiler: boolean;
    video: Video | null;
    user: User | null;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.post_date = (obj_.post_date !== undefined) ? obj_.post_date : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
        this.spoiler = (obj_.spoiler !== undefined) ? obj_.spoiler : null;
        this.video = (obj_.video !== undefined) ? (obj_.video !== null ? new Video(obj_.video): null) : null;
        this.user = (obj_.user !== undefined) ? (obj_.user !== null ? new User(obj_.user): null) : null;
    }
}
export const CommentVideoFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class CommentNews {
    id: number;
    post_date: Date;
    description: string;
    user: User | null;
    news: News | null;

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.post_date = (obj_.post_date !== undefined) ? obj_.post_date : null;
        this.description = (obj_.description !== undefined) ? obj_.description : null;
        this.user = (obj_.user !== undefined) ? (obj_.user !== null ? new User(obj_.user): null) : null;
        this.news = (obj_.news !== undefined) ? (obj_.news !== null ? new News(obj_.news): null) : null;
    }
}
export const CommentNewsFlags = {
    NORMAL: {name: "NORMAL", value: 0},
    ALL: {name: "ALL", value: 1}
};
export class APIWebFile {
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

    public constructor(obj?: any){ 
        const obj_: any = obj || {};
        this.id = (obj_.id !== undefined) ? obj_.id : null;
        this.name = (obj_.name !== undefined) ? obj_.name : null;
        this.type = (obj_.type !== undefined) ? obj_.type : null;
        this.size = (obj_.size !== undefined) ? obj_.size : null;
        this.tmp_name = (obj_.tmp_name !== undefined) ? obj_.tmp_name : null;
        this.error = (obj_.error !== undefined) ? obj_.error : null;
        this.full_path = (obj_.full_path !== undefined) ? obj_.full_path : null;
        this.extension = (obj_.extension !== undefined) ? obj_.extension : null;
        this.system_path = (obj_.system_path !== undefined) ? obj_.system_path : null;
        this.web_path = (obj_.web_path !== undefined) ? obj_.web_path : null;
    }
}
export const flags : any = { 
    "GlobalSettingFlags" : GlobalSettingFlags,
    "ResourceFlags" : ResourceFlags,
    "LanguageFlags" : LanguageFlags,
    "UserFlags" : UserFlags,
    "SourceTypeFlags" : SourceTypeFlags,
    "AudienceFlags" : AudienceFlags,
    "AnimeFlags" : AnimeFlags,
    "SeasonFlags" : SeasonFlags,
    "VideoTypeFlags" : VideoTypeFlags,
    "VideoFlags" : VideoFlags,
    "SubtitleFlags" : SubtitleFlags,
    "DubbingFlags" : DubbingFlags,
    "PunishmentTypeFlags" : PunishmentTypeFlags,
    "PunishmentFlags" : PunishmentFlags,
    "GenderFlags" : GenderFlags,
    "TicketFlags" : TicketFlags,
    "TicketMessageFlags" : TicketMessageFlags,
    "RoleFlags" : RoleFlags,
    "PermissionFlags" : PermissionFlags,
    "AccountPlanFlags" : AccountPlanFlags,
    "AccountPurchaseFlags" : AccountPurchaseFlags,
    "LogActionFlags" : LogActionFlags,
    "LogFlags" : LogFlags,
    "NewsFlags" : NewsFlags,
    "NewsBodyFlags" : NewsBodyFlags,
    "CommentAnimeFlags" : CommentAnimeFlags,
    "CommentVideoFlags" : CommentVideoFlags,
    "CommentNewsFlags" : CommentNewsFlags
}
export const models : any = { 
    "GlobalSetting" : GlobalSetting,
    "Resource" : Resource,
    "Language" : Language,
    "User" : User,
    "SourceType" : SourceType,
    "Audience" : Audience,
    "Anime" : Anime,
    "Season" : Season,
    "VideoType" : VideoType,
    "Video" : Video,
    "Subtitle" : Subtitle,
    "Dubbing" : Dubbing,
    "PunishmentType" : PunishmentType,
    "Punishment" : Punishment,
    "Gender" : Gender,
    "Ticket" : Ticket,
    "TicketMessage" : TicketMessage,
    "Role" : Role,
    "Permission" : Permission,
    "AccountPlan" : AccountPlan,
    "AccountPurchase" : AccountPurchase,
    "LogAction" : LogAction,
    "Log" : Log,
    "News" : News,
    "NewsBody" : NewsBody,
    "CommentAnime" : CommentAnime,
    "CommentVideo" : CommentVideo,
    "CommentNews" : CommentNews,
    "APIWebFile" : APIWebFile
}