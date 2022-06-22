export declare class Request {
    private static URL;
    static requestService(service: string, action: any, data: {}, flags: string[]): Promise<unknown>;
    static requestType(target: string, action: any, data: {}, flags: string[]): Promise<unknown>;
    private static sendRequest;
}
