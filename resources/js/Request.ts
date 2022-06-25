import {models} from "./models";

export class Request {

    private static URL: string = "../API/v1/";

    public static requestService(service: string, action: any, data: {}, flags: string[]) {
        return this.sendRequest({
            "service": service,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "data": [data]
        });
    }

    public static requestType(target: string, action: any, data: {}, flags: string[]) {
        return this.sendRequest({
            "type": target,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "data": [data]
        });
    }


    private static sendRequest(array: {}) {
        return new Promise(function (resolve, reject) : any {
            let request = new XMLHttpRequest();
            request.open("POST", Request.URL, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.responseType = 'json';

            request.onload = function () {
                if (request.status >= 200 && request.status < 300) {
                    let _ret : any[];
                    if("dataTypes" in request.response && request.response.dataTypes !== null){
                        _ret = Request.buildElement(request.response.dataTypes, request.response.data);
                    } else {
                        _ret = request.response.data;
                    }
                    let response = request.response;
                    delete response.dataTypes;
                    response.data = _ret;
                    resolve(response);
                } else {
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