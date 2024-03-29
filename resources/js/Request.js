import { models } from "./models";
export class WebFile {
    constructor(obj) {
        const obj_ = obj || {};
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
    static requestService(service, action, data = {}, flags = []) {
        return this.sendRequest({
            "service": service,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "data": [data]
        });
    }
    static requestType(target, action, data = {}, flags = [], minimal = null, entities = null, operator = "=") {
        let request = {
            "type": target,
            "action": action,
            "dataTypes": true,
            "flags": flags,
            "operator": operator,
            "data": [data]
        };
        if (minimal !== null)
            request["minimal"] = minimal;
        if (entities !== null)
            request["entities"] = entities;
        return this.sendRequest(request);
    }
    static uploadFile(file) {
        return new Promise(function (resolve) {
            let formData = new FormData();
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
            }).done(function (response) {
                if (response.status) {
                    if ("data" in response) {
                        response.data = new WebFile(response.data[0]);
                    }
                }
                ret = response;
            });
            resolve(ret);
            return ret;
        });
    }
    static sendRequest(array) {
        return new Promise(function (resolve, reject) {
            let request = new XMLHttpRequest();
            request.open("POST", Request.API_URL, true);
            request.setRequestHeader("Content-Type", "application/json");
            //request.setRequestHeader('Content-Type', 'multipart/form-data');
            //request.responseType = 'json';
            request.onload = function () {
                if (request.status >= 200 && request.status < 300) {
                    if (isJson(request.response)) {
                        let response = JSON.parse(request.response);
                        if (response !== null) {
                            response.original = response.data;
                            let _ret;
                            if ("dataTypes" in response && response.dataTypes !== null) {
                                _ret = Request.buildElement(response.dataTypes, response.data);
                            }
                            else {
                                _ret = response.data;
                            }
                            delete response.dataTypes;
                            response.data = _ret;
                        }
                        resolve(response);
                    }
                    else {
                        console.error(request.response);
                    }
                }
                else {
                    console.error({
                        request
                    });
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
                                _objs[item] = data[item];
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
Request.API_URL = new URL("../../API/v1/", import.meta.url).href;
Request.UPLOAD_FILE_URL = new URL("../../API/v1/uploadFile.php", import.meta.url).href;
function isJson(str) {
    try {
        JSON.parse(str);
    }
    catch (e) {
        return false;
    }
    return true;
}
