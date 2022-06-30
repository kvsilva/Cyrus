export declare class WebFile {
    name: String;
    type: String;
    size: number;
    tmp_name: string;
    error: number;
    full_path: string;
    extension: string;
    system_path: string;
    web_path: string;
    constructor(obj?: any);
}
export declare class Request {
    private static API_URL;
    private static UPLOAD_FILE_URL;
    static requestService(service: string, action: any, data?: {}, flags?: string[]): Promise<unknown>;
    static requestType(target: string, action: any, data?: {}, flags?: string[]): Promise<unknown>;
    static uploadFile(file: File): Promise<unknown>;
    private static sendRequest;
    private static buildElement;
}
