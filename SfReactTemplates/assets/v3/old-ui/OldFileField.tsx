import React, { Fragment } from 'react'
import FileUploadWidget from './OldFileUploadWidget'
import { getLinkForFile, getViewLinkForFile } from './OldFileFieldRo';
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle';

interface Props {
    property: any,
    val: any,
    onChange: (val: any) => void,
    width?: string,
    allowMultiple?: boolean,
}

export default function OldFileField(props: Props) {
    const { property } = props;

    const doDownload = () => {
        const link = getLinkForFile(props.val);
        window.open(link, '_blank');
    }

    const doRemove = () => {
        props.onChange({})
    }

    let ext = '';
    if (props.val && props.val.filename) {
        const _name = props.val.filename.split('.');
        ext = _name[_name.length - 1];
    }

    const openFile = () => {
        const options = {
            file: {
                title: props.val.filename,
                onView: {
                    link: getViewLinkForFile(props.val),
                    ext: ext,
                    id: props.val.id,
                },
                onDownload: () => {
                    const link = getLinkForFile(props.val)
                    window.open(link, '_blank')
                },
                onRemove: doRemove

            }
        }
        const event = new CustomEvent(
            'SFSOpenFilePreview',
            {
                detail: options
            }
        );
        window.dispatchEvent(event);
    }

    return (
        <Fragment>
            <div className={`tw3-flex tw3-gap-2 tw3-items-center ${props.width ? props.width : 'tw3-w-96 tw3-max-w-96'}`}>
                {props.val && props.val.filename ?
                    <Fragment>
                        <p className={'tw3-flex-grow tw3-truncate'}>{props.val.filename}</p>

                        <ToolbarButton
                            iconName='eye'
                            onClick={openFile}
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
        </Fragment>
    )
}
