import {Request as API} from "../../../../resources/js/Request";
import {Resource, User} from "../../../../resources/js/models";

let user: User | null = null;

$(document).ready(async function () {

    // @ts-ignore
    $("#form0-body").summernote({
        height: 550
    });
    $('.dropdown-toggle').dropdown();


    await API.requestService("session", "getSession").then((result: any) => {
        if (result.status && result.data) user = result.data[0];
    });

    $("#form0-submit").on("click", async function (e) {
        e.preventDefault();
        //@ts-ignore
        let title: string = $("#form0-title").val();
        //@ts-ignore
        let subtitle: string = $("#form0-subtitle").val();
        //@ts-ignore
        let preview: string = $("#form0-preview").val();
        // @ts-ignore
        let body: string = $("#form0-body").summernote('code');

        if (title.length > 0 && subtitle.length > 0 && preview.length > 0 && body.length > 0 && $("#form0-thumbnail").prop("files").length > 0) {
            let attachment: Resource|null = null;
            await API.uploadFile($("#form0-thumbnail").prop("files")[0]).then(async (result2: any) => {
                if (result2.status && result2.data) {
                    await API.requestService("Resources", "uploadFile", {
                        file: result2.data,
                    }).then((result3: any) => {
                        if (result3.status && result3.data) {
                            attachment = (result3.data[0]);
                        } else {
                            // @ts-ignore
                            cyrusAlert("danger", "Ocorreu um erroa ao anexar os ficheiros em anexo ao sistema. Consulte a consola para mais informações.");
                            console.error(result3);
                            return;
                        }
                    });
                } else {
                    // @ts-ignore
                    cyrusAlert("danger", "Ocorreu um erroa ao fazer o upload dos ficheiros em anexo. Consulte a consola para mais informações.");
                    console.error(result2);
                    return;
                }
            });

            let formData: any[string] = {
                user: user?.id,
                spotlight: true,
                relations: {
                    "NEWSBODY": [
                        {
                            user: user?.id,
                            content: body,
                            title: title,
                            subtitle: subtitle,
                            preview: preview,
                            thumbnail: attachment
                        }
                    ]
                }
            };

            API.requestType("news", "insert", formData).then((result: any) => {
                if(result.status && result.data){
                    setTimeout(async function () {
                        window.location.href = new URL("../../../?news=" + result.data[0]?.id, import.meta.url).href;
                    }, 2000)
                    // @ts-ignore
                    cyrusAlert("success", "Notícia criada com sucesso. Redirecionando...");
                } else {
                    // @ts-ignore
                    cyrusAlert("danger", "Ocorreu um erroa ao criar a notícia. Consulte a consola para mais informações.");
                }
            });
        } else {
            // @ts-ignore
            cyrusAlert("danger", "Ocorreu um erroa ao criar a notícia. Consulte a consola para mais informações.");
        }
    });
});
