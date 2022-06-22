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
                    let _ret : any[] = [];
                    if("dataTypes" in request.response && request.response.dataTypes !== null){
                        for(let i = 0; i < request.response.dataTypes.length; i++){
                            let _class : string = request.response.dataTypes[i];
                            let _obj : any;
                            console.log(request.response.data[i]);
                            if(_class !== "Unknown") {
                                _obj = new models[_class](request.response.data[i]);
                            } else {

                                _obj = request.response.data[i];
                            }
                            _ret.push(_obj);
                        }
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
}