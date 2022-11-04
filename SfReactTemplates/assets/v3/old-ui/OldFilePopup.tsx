import { WhiteCard } from "@newageerp/v3.widgets.white-card";
import React, { useState, useRef, Fragment, useEffect } from "react";
// @ts-ignore
import FileViewer from "react-file-viewer";

import { useTranslation } from "react-i18next";
import Button, { ButtonBgColor } from './OldButton'
import OldPopoverConfirm from "./OldPopoverConfirm";
import { OldUiH5 } from "./OldUiH5";


export interface IFilePopupItem {
    link: string,
    type: string,
    id: number,
    name: string,
    path: string,
}

interface Props {
    visible: boolean;
    toggleVisible: () => void;

    onRemove?: (f: IFilePopupItem) => void;
    printOnLoad?: boolean;

    onEmailClick?: (f: IFilePopupItem) => void;

    otherFiles?: IFilePopupItem[];
    file: IFilePopupItem
}

export default function OldFilePopup(props: Props) {
    const ref = useRef(null);

    const [currentFile, setCurrentFile] = useState<IFilePopupItem>(props.file);

    const { t } = useTranslation();

    const doDownload = () => {
        window.open(currentFile.link);
    };

    const doPrint = () => {
        if (ref && ref.current) {
            // @ts-ignore
            ref.current.contentWindow.print();
        }
    };

    useEffect(() => {
        if (props.printOnLoad) {
            setTimeout(doPrint, 500);
        }
    }, [props.printOnLoad]);

    const isPdf = !!currentFile && currentFile.name.indexOf(".pdf") >= 0;

    const handleKeyDown = (event: any) => {
        // ESC
        if (event.keyCode === 27) {
            event.stopPropagation();
            props.toggleVisible();
        }
    };

    if (!props.visible) {
        return <Fragment />
    }

    return (
        <Fragment>
            <div className={"overscroll-none fixed inset-0 h-screen w-screen z-50 "} onKeyDown={handleKeyDown} tabIndex={-1}>
                <div className={"flex gap-2 h-screen pt-4 px-4 bg-gradient-to-b from-gray-400 via-gray-200 overscroll-none"}>

                    <div
                        className={"flex-grow rounded-md bg-white"}
                    >
                        {isPdf ?
                            <iframe
                                className={"w-full h-full"}
                                src={
                                    currentFile.link.replace('nae-core/files/download', 'nae-core/files/view')
                                }
                                ref={ref}
                            ></iframe>
                            : <FileViewer
                                key={"file_" + currentFile.id}
                                fileType={currentFile.type}
                                filePath={currentFile.link}
                            />}

                    </div>

                    <div className={"w-80 space-y-1"} style={{minWidth: '20rem'}}>
                        <Button
                            brightness={200}
                            bgColor={ButtonBgColor.gray}
                            onClick={props.toggleVisible}
                            className={"w-full mb-6"}
                            icon="fal fa-times-circle"
                        >

                            {t('Uždaryti')}
                        </Button>

                        <Button
                            className={"w-full"}
                            brightness={200}
                            onClick={doDownload}
                            icon={"fas fa-download"}
                        >
                            {t("Atsisiųsti")}
                        </Button>

                        {isPdf &&
                            <Button
                                className={"w-full"}
                                brightness={200}
                                onClick={doPrint}
                                icon={"fas fa-print"}
                            >
                                {t("Spausdinti")}
                            </Button>
                        }

                        {!!props.onEmailClick &&
                            <Button
                                className={"w-full"}
                                brightness={200}
                                onClick={() => {
                                    if (props.onEmailClick) {
                                        props.onEmailClick(currentFile)
                                    }
                                }}
                                icon={"fas fa-paper-plane"}
                            >
                                {t("Siųsti")}
                            </Button>
                        }

                        {!!props.onRemove && (
                            <OldPopoverConfirm onClick={() => {
                                if (props.onRemove) {
                                    props.onRemove(currentFile);
                                }
                            }}>
                                <Button
                                    className={"w-full"}
                                    brightness={200}
                                    bgColor={ButtonBgColor.red}
                                    icon={"fas fa-paper-plane"}
                                >
                                    {t("Ištrinti")}
                                </Button>
                            </OldPopoverConfirm>
                        )}

                        {!!props.otherFiles && props.otherFiles.length > 1 && (
                            <WhiteCard className={"mt-2"}>

                                <OldUiH5>{"Kiti failai"}</OldUiH5>

                                <div className={"grid gap-1"}>
                                    {props.otherFiles
                                        .map((f: IFilePopupItem, index: number) => {
                                            return (
                                                <div key={"file-preview-" + index}>
                                                    <button
                                                        className={
                                                            "text-sm hover:underline " + (
                                                                f.id === currentFile.id
                                                                    ? "text-blue-500"
                                                                    : "text-gray-500"
                                                            )
                                                        }
                                                        onClick={() => setCurrentFile(f)}
                                                    >
                                                        {f.name}
                                                    </button>
                                                </div>
                                            );
                                        })}
                                </div>
                            </WhiteCard>
                        )}
                    </div>
                </div>
            </div>

        </Fragment>
    );
}
