import { models } from "./models";
export class Request {
    static requestService(service, action, data, flags) {
        return this.sendRequest({
            "service": service,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "data": [data]
        });
    }
    static requestType(target, action, data, flags) {
        return this.sendRequest({
            "type": target,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "data": [data]
        });
    }
    static sendRequest(array) {
        return new Promise(function (resolve, reject) {
            let request = new XMLHttpRequest();
            request.open("POST", Request.URL, true);
            request.setRequestHeader("Content-Type", "application/json");
            request.responseType = 'json';
            request.onload = function () {
                if (request.status >= 200 && request.status < 300) {
                    let _ret;
                    if ("dataTypes" in request.response && request.response.dataTypes !== null) {
                        _ret = Request.buildElement(request.response.dataTypes, request.response.data);
                    }
                    else {
                        _ret = request.response.data;
                    }
                    let response = request.response;
                    delete response.dataTypes;
                    response.data = _ret;
                    resolve(response);
                }
                else {
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
    static buildElement(dataType, data) {
        let _objs = [];
        if (Array.isArray(dataType)) {
            for (let i = 0; i < dataType.length; i++) {
                if (typeof data === 'object') {
                    let x = 0;
                    for (const item in data) {
                        if (x == i) {
                            let _class = dataType[i];
                            let _obj;
                            if (_class !== "Unknown" && models[_class] !== undefined) {
                                // @ts-ignore
                                _obj = new models[_class](data[item]);
                                _objs.push(_obj);
                            }
                            else {
                                // @ts-ignore
                                _objs[i] = data[item];
                            }
                        }
                        x++;
                    }
                }
                else {
                    let _class = dataType[i];
                    let _obj;
                    if (_class !== "Unknown" && models[_class] !== undefined) {
                        // @ts-ignore
                        _obj = new models[_class](data[i]);
                        _objs.push(_obj);
                    }
                    else {
                        // @ts-ignore
                        _objs[i] = data[i];
                    }
                }
            }
        }
        else if (typeof dataType === 'object') {
            // @ts-ignore
            for (const item in dataType) {
                if (typeof dataType[item] === 'object') {
                    // @ts-ignore
                    _objs[item] = this.buildElement(dataType[item], data[item]);
                }
                else {
                    let _class = dataType[item];
                    let _obj;
                    if (_class !== "Unknown" && models[_class] !== undefined) {
                        // @ts-ignore
                        _obj = new models[_class](data[item]);
                        _objs.push(_obj);
                    }
                    else {
                        // @ts-ignore
                        _objs[item] = data[item];
                    }
                }
            }
        }
        return _objs;
    }
}
Request.URL = "../API/v1/";
