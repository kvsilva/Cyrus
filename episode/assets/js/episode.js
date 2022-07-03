var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Request as API } from "../../../resources/js/Request";
import { UserFlags } from "../../../resources/js/models";
let user = null;
$(document).ready(function () {
    return __awaiter(this, void 0, void 0, function* () {
        yield API.requestService("session", "getSession", {}, []).then((result) => __awaiter(this, void 0, void 0, function* () {
            if (result.status) {
                if ("data" in result) {
                    user = result.data[0];
                    API.requestType("User", "update", {
                        "id": user === null || user === void 0 ? void 0 : user.id,
                        "relations": {
                            "VideoHistory": [
                                {
                                    "video": getParameter("episode"),
                                    "date": null,
                                    "watched_until": null
                                }
                            ]
                        }
                    }, [UserFlags.VIDEOHISTORY.name], false).then((result) => {
                        var _a, _b;
                        if (result.status) {
                            if ("data" in result) {
                                let loc = (_a = result.data[0].video_history) === null || _a === void 0 ? void 0 : _a.filter((value) => { var _a; return ((_a = value.video) === null || _a === void 0 ? void 0 : _a.id) == parseInt(getParameter("episode")); });
                                if (loc.length > 0) {
                                    // @ts-ignore
                                    (_b = document.getElementById("player0")) === null || _b === void 0 ? void 0 : _b.currentTime = loc[0].watched_until;
                                }
                            }
                        }
                    });
                }
                updateTime();
            }
        }));
    });
});
function updateTime() {
    if (user !== null) {
        setTimeout(function () {
            var _a;
            // @ts-ignore
            let time = Math.floor((_a = document.getElementById("player0")) === null || _a === void 0 ? void 0 : _a.currentTime);
            API.requestType("User", "update", {
                "id": user === null || user === void 0 ? void 0 : user.id,
                "relations": {
                    "VideoHistory": [
                        {
                            "video": getParameter("episode"),
                            "date": null,
                            "watched_until": time
                        }
                    ]
                }
            }, [UserFlags.VIDEOHISTORY.name]).then((result) => {
                if (result.status) {
                    if ("data" in result) {
                    }
                }
            });
            updateTime();
        }, 10000);
    }
}
function getParameter(parameter) {
    // Address of the current window
    let address = window.location.search;
    // Returns a URLSearchParams object instance
    let parameterList = new URLSearchParams(address);
    // Returning the respected value associated
    // with the provided key
    return parameterList.get(parameter);
}
