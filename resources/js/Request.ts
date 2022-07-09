import {models} from "./models";

export class WebFile{
    name: String;
    type: String;
    size: number;
    tmp_name: string;
    error: number;
    full_path: string;
    extension: string;
    system_path: string;
    web_path: string;

    public constructor(obj?: any){
        const obj_: any = obj || {};
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

export class Request {

    private static API_URL: string = new URL("../../API/v1/", import.meta.url).href;
    private static UPLOAD_FILE_URL: string = new URL("../../API/v1/uploadFile.php", import.meta.url).href;

    public static requestService(service: string, action: any, data: {} = {}, flags: string[] = []) {
        return this.sendRequest({
            "service": service,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "data": [data]
        });
    }

    public static requestType(target: string, action: any, data: {} = {}, flags: string[] = [], minimal: boolean | null = null, entities: boolean | null = null, operator = "=") {
        let request : any[string] = {
            "type": target,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "operator": operator,
            "data": [data]
        };
        if(minimal !== null) request["minimal"] = minimal;
        if(entities !== null) request["entities"] = entities;
        return this.sendRequest(request);
    }

    public static uploadFile(file: File) {
        return new Promise(function (resolve) : any {
            let formData : FormData = new FormData();
            formData.append("files", file);
            let ret = null;
            $.ajax({
                url: Request.UPLOAD_FILE_URL,
                type: "POST",
                contentType: false,
                processData: false,
                data: formData,
                async: false,
                dataType: "json"
            }).done(function (response : any){
                if(response.status){
                    if("data" in response){
                        response.data = new WebFile(response.data[0]);
                    }
                }
                ret = response;
            });
            resolve(ret);
            return ret;
        });
    }


    private static sendRequest(array: {}) {
        return new Promise(function (resolve, reject) : any {
            let request = new XMLHttpRequest();
            request.open("POST", Request.API_URL, true);
            request.setRequestHeader("Content-Type", "application/json");
            //request.setRequestHeader('Content-Type', 'multipart/form-data');
            //request.responseType = 'json';

            request.onload = function () {
                if (request.status >= 200 && request.status < 300) {
                    if(isJson(request.response)) {
                        let response = JSON.parse(request.response);
                        if (response !== null) {
                            response.original = response.data;
                            let _ret: any[];
                            if ("dataTypes" in response && response.dataTypes !== null) {
                                _ret = Request.buildElement(response.dataTypes, response.data);
                            } else {
                                _ret = response.data;
                            }
                            delete response.dataTypes;
                            response.data = _ret;
                        }
                        resolve(response);
                    } else {
                        console.error(request.response);
                    }
                } else {
                    console.error({
                        request
                    })
                    reject({
                        status: request.status,
                        statusText: request.statusText
                    });
                }
            };
            request.onerror = function () {
                reject({
                    status: request.status,
                    statusText: request.statusText
                });
            };

            request.send(JSON.stringify(array));
        });
    }

    private static buildElement(dataType : any[], data: any[]){
        let _objs : any [] = [];
        if(Array.isArray(dataType)) {
            for (let i = 0; i < dataType.length; i++) {
                if(typeof data === 'object'){
                    let x : number = 0;
                    for (const item in data) {
                        if(x == i) {
                            let _class: string = dataType[i];
                            let _obj: any;
                            if (_class !== "Unknown" && models[_class] !== undefined) {
                                // @ts-ignore
                                _obj = new models[_class](data[item]);
                                _objs.push(_obj);
                            } else {
                                // @ts-ignore
                                _objs[item] = data[item];
                            }
                        }
                        x++;
                    }
                } else {
                    let _class: string = dataType[i];
                    let _obj: any;
                    if (_class !== "Unknown" && models[_class] !== undefined) {
                        // @ts-ignore
                        _obj = new models[_class](data[i]);
                        _objs.push(_obj);
                    } else {
                        // @ts-ignore
                        _objs[i] = data[i];
                    }
                }
            }
        } else if (typeof dataType === 'object'){
            // @ts-ignore
            for (const item in dataType) {
                if(typeof dataType[item] === 'object'){
                    // @ts-ignore
                    _objs[item] = this.buildElement(dataType[item], data[item]);
                } else {
                    let _class: string = dataType[item];
                    let _obj: any;
                    if (_class !== "Unknown" && models[_class] !== undefined) {
                        // @ts-ignore
                        _obj = new models[_class](data[item]);
                        _objs.push(_obj);
                    } else {
                        // @ts-ignore
                        _objs[item] = data[item];
                    }
                }
            }
        }
        return _objs;
    }
}

function isJson(str: string) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}