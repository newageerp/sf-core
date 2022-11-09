import React, { Fragment, useState } from 'react'
import FileUploadWidget from './OldFileUploadWidget'
import { PopupFile } from '@newageerp/ui.popups.base.popup-file';
import { FilesWindow } from '@newageerp/ui.files.files.files-window';
import { getLinkForFile } from './OldFileFieldRo';
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';

interface Props {
    property: any,
    val: any,
    onChange: (val: any) => void,
    width?: string,
    allowMultiple?: boolean,
}

export default function OldFileField(props: Props) {
    const [showFilePoup, setShowFilePopup] = useState(false);
    const toggleShowFilePopup = () => setShowFilePopup(!showFilePoup);

    const { property } = props;

    const doDownload = () => {
        const link = getLinkForFile(props.val);
        window.open(link, '_blank');
    }

    const doPreview = () => {
        toggleShowFilePopup();
    }

    const doRemove = () => {
        props.onChange({})
    }

    let ext = '';
    if (props.val && props.val.filename) {
        const _name = props.val.filename.split('.');
        ext = _name[_name.length - 1];
    }

    return (
        <Fragment>
            <div className={`tw3-flex tw3-gap-2 tw3-items-center ${props.width ? props.width : 'tw3-w-96 tw3-max-w-96'}`}>
                {props.val && props.val.filename ?
                    <Fragment>
                        <p className={'tw3-flex-grow tw3-truncate'}>{props.val.filename}</p>

                        <ToolbarButton
                            iconName='eye'
                            onClick={doPreview}
                        />
                        <ToolbarButton
                            iconName='download'
                            onClick={doDownload}
                        />
                        <ToolbarButton
                            iconName='window-close'
                            onClick={doRemove}
                            textColor={"tw3-text-red-500"}
                        />
                    </Fragment>
                    :
                    <FileUploadWidget
                        type={property.key}
                        folderPrefix={property.schema}
                        onUpload={(nfiles) => {
                            if (props.allowMultiple) {
                                const _files = Object.keys(nfiles).map((k: string) => {
                                    const f = nfiles[k]
                                    return f;
                                })
                                props.onChange(_files);
                            } else {
                                Object.keys(nfiles).map((k: string) => {
                                    const f = nfiles[k]
                                    props.onChange(f);
                                })
                            }

                        }}
                        hideCard={true}
                        hideTitle={true}
                    />
                }
            </div>
            {props.val && props.val.filename && showFilePoup &&
                <PopupFile onClose={toggleShowFilePopup}>
                    <FilesWindow
                        file={{
                            title: props.val.filename,
                            onView: {
                                link: getLinkForFile(props.val),
                                ext: ext,
                                id: props.val.id,
                            },
                            onDownload: () => {
                                const link = getLinkForFile(props.val)
                                window.open(link, '_blank')
                            },
                            onRemove: doRemove

                        }}
                        onBack={toggleShowFilePopup}

                    />
                </PopupFile>
            }

        </Fragment>
    )
}
