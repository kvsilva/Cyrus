var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
import { Request as API } from "../../../../resources/js/Request";
let user = null;
$(document).ready(function () {
    return __awaiter(this, void 0, void 0, function* () {
        // @ts-ignore
        $("#form0-body").summernote({
            height: 550
        });
        $('.dropdown-toggle').dropdown();
        yield API.requestService("session", "getSession").then((result) => {
            if (result.status && result.data)
                user = result.data[0];
        });
        $("#form0-submit").on("click", function (e) {
            return __awaiter(this, void 0, void 0, function* () {
                e.preventDefault();
                //@ts-ignore
                let title = $("#form0-title").val();
                //@ts-ignore
                let subtitle = $("#form0-subtitle").val();
                //@ts-ignore
                let preview = $("#form0-preview").val();
                // @ts-ignore
                let body = $("#form0-body").summernote('code');
                let spotlight = $("#form0-spotlight").prop("checked");
                if (title.length > 0 && subtitle.length > 0 && preview.length > 0 && body.length > 0 && $("#form0-thumbnail").prop("files").length > 0) {
                    let attachment = null;
                    yield API.uploadFile($("#form0-thumbnail").prop("files")[0]).then((result2) => __awaiter(this, void 0, void 0, function* () {
                        if (result2.status && result2.data) {
                            yield API.requestService("Resources", "uploadFile", {
                                file: result2.data,
                            }).then((result3) => {
                                if (result3.status && result3.data) {
                                    attachment = (result3.data[0]);
                                }
                                else {
                                    // @ts-ignore
                                    cyrusAlert("danger", "Ocorreu um erroa ao anexar os ficheiros em anexo ao sistema. Consulte a consola para mais informações.");
                                    console.error(result3);
                                    return;
                                }
                            });
                        }
                        else {
                            // @ts-ignore
                            cyrusAlert("danger", "Ocorreu um erroa ao fazer o upload dos ficheiros em anexo. Consulte a consola para mais informações.");
                            console.error(result2);
                            return;
                        }
                    }));
                    let formData = {
                        user: user === null || user === void 0 ? void 0 : user.id,
                        spotlight: spotlight,
                        relations: {
                            "NEWSBODY": [
                                {
                                    user: user === null || user === void 0 ? void 0 : user.id,
                                    content: body,
                                    title: title,
                                    subtitle: subtitle,
                                    preview: preview,
                                    thumbnail: attachment
                                }
                            ]
                        }
                    };
                    API.requestType("news", "insert", formData).then((result) => {
                        if (result.status && result.data) {
                            setTimeout(function () {
                                var _a;
                                return __awaiter(this, void 0, void 0, function* () {
                                    window.location.href = new URL("../../../?news=" + ((_a = result.data[0]) === null || _a === void 0 ? void 0 : _a.id), import.meta.url).href;
                                });
                            }, 2000);
                            // @ts-ignore
                            cyrusAlert("success", "Notícia criada com sucesso. Redirecionando...");
                        }
                        else {
                            // @ts-ignore
                            cyrusAlert("danger", "Ocorreu um erroa ao criar a notícia. Consulte a consola para mais informações.");
                        }
                    });
                }
                else {
                    // @ts-ignore
                    cyrusAlert("danger", "Ocorreu um erroa ao criar a notícia. Consulte a consola para mais informações.");
                }
            });
        });
    });
});
