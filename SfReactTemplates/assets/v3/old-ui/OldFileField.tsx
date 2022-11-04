import React, { Fragment, useState } from 'react'
import FileUploadWidget from './OldFileUploadWidget'
import Button, { ButtonBgColor, ButtonSize } from './OldButton';
import { getLinkForFile } from './OldFileFieldRo';
import FilePopup from './OldFilePopup';

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
            <div className={`flex gap-2 items-center ${props.width ? props.width : 'w-96'}`}>
                {props.val && props.val.filename ?
                    <Fragment>
                        <p>{props.val.filename}</p>

                        <Button
                            size={ButtonSize.sm}
                            bgColor={ButtonBgColor.nsecondary}
                            brightness={50}
                            icon={"fal fa-eye"}
                            onClick={doPreview}
                        >
                        </Button>

                        <Button
                            size={ButtonSize.sm}
                            bgColor={ButtonBgColor.nsecondary}
                            brightness={50}
                            icon={"fal fa-download"}
                            onClick={doDownload}
                        >
                        </Button>

                        <Button
                            size={ButtonSize.sm}
                            bgColor={ButtonBgColor.red}
                            brightness={50}
                            icon={"fal fa-window-close"}
                            onClick={doRemove}
                        >
                        </Button>
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
            {props.val && props.val.filename &&
                <FilePopup
                    visible={showFilePoup}
                    printOnLoad={false}
                    toggleVisible={toggleShowFilePopup}

                    onRemove={doRemove}

                    file={{
                        name: props.val.filename,
                        link: getLinkForFile(props.val),
                        type: ext,
                        path: props.val.path,
                        id: 0,
                    }}
                />
            }
        </Fragment>
    )
}
