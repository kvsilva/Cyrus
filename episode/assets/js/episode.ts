import {Request as API} from "../../../resources/js/Request";
import {User, UserFlags} from "../../../resources/js/models";

let user : User|null = null;

$(document).ready(async function () {
    await API.requestService("session", "getSession", {}, []).then(async (result: any) => {
        if (result.status) {
            if ("data" in result) {
                user = result.data[0];
                API.requestType("User", "update", {
                    "id": user?.id,
                    "relations": {
                        "VideoHistory":
                            [
                                {
                                    "video": getParameter("episode"),
                                    "date": null,
                                    "watched_until": null
                                }
                            ]
                    }
                }, [UserFlags.VIDEOHISTORY.name], false).then((result: any) => {
                    if (result.status) {
                        if ("data" in result) {
                            let loc: any[] = result.data[0].video_history?.filter((value: any) => value.video?.id == parseInt(<string>getParameter("episode")));
                            if (loc.length > 0) {
                                // @ts-ignore
                                document.getElementById("player0")?.currentTime = loc[0].watched_until;
                            }
                        }
                    }
                });
            }
            updateTime();
        }
    });
});

function updateTime(){
    if(user !== null) {
        setTimeout(function () {
            // @ts-ignore
            let time = Math.floor(document.getElementById("player0")?.currentTime);
            API.requestType("User", "update", {
                "id": user?.id,
                "relations":{
                    "VideoHistory":
                        [
                            {
                                "video": getParameter("episode"),
                                "date": null,
                                "watched_until": time
                            }
                        ]
                }
            }, [UserFlags.VIDEOHISTORY.name]).then((result: any) => {
                if (result.status) {
                    if ("data" in result) {

                    }

                }
            });
            updateTime();
        }, 10000);
    }
}

function getParameter(parameter: string) {

    // Address of the current window
    let address = window.location.search

    // Returns a URLSearchParams object instance
    let parameterList = new URLSearchParams(address)

    // Returning the respected value associated
    // with the provided key
    return parameterList.get(parameter)
}