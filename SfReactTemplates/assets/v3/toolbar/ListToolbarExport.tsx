import React, { ReactNode, useRef } from 'react';
import { Fragment } from 'react';
import axios from 'axios';
import { useState } from 'react';
import { useTranslation } from 'react-i18next';
import { ToolbarButtonWithMenu } from '@newageerp/v3.bundles.buttons-bundle'
import { ToolbarButton } from '@newageerp/v3.bundles.buttons-bundle'
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { MenuItem } from '@newageerp/v3.bundles.modal-bundle'
import { ISummary } from '../list/ListDataSummary';
import { axiosInstance } from '@newageerp/v3.bundles.utils-bundle';

export type ExportContainerProps = {
    exports: ITabExport[];
    schema: string;
    exportOptions?: any;
    summary?: ISummary[];
};

export type ITabExportField = {
    path: string;
    customTitle?: string;
    allowEdit?: boolean;
};

export type ITabExport = {
    title: string;
    type: string;
    columns: ITabExportField[];
};

export function ListToolbarExport(props: ExportContainerProps) {
    const { data: tData } = useTemplatesLoader();

    const ref = useRef();

    const { t } = useTranslation();
    const [uploading, setUploading] = useState(false);

    const [getData, getDataParams] = OpenApi.useURequest('NAEUExport');

    let canUpload = false;
    props.exports.forEach(m => {
        m.columns.forEach(f => {
            if (f.allowEdit) {
                canUpload = true;
            }
        })
    })

    const doDownload = (ex: ITabExport) => {
        let fieldsToReturn: string[] = [];

        ex.columns.forEach((f: ITabExportField) => {
            const pathA = f.path.split('.');
            pathA.shift();
            fieldsToReturn.push(pathA.join('.'));
        });

        getData({
            exportOptions: {
                filter: tData.filter.prepareFilter(),
                fieldsToReturn: fieldsToReturn,
                sort: tData.sort.value,
                summary: props.summary
            },
            schema: props.schema,
            columns: ex.columns,
            title: ex.title,
        }).then((res: any) => {
            if (res.data.url) {
                window.open(res.data.url, '_blank');
            }
        });
    };

    const doUpload = (e: any) => {
        if (uploading) {
            return;
        }
        setUploading(true);

        const fData = new FormData();
        fData.append('schema', props.schema);
        fData.append('file', e.target.files[0]);

        // @ts-ignore
        const token: string = window.localStorage.getItem('token');

        axiosInstance
            .post('/app/nae-core/import/mainImport', fData, {
                headers: {
                    Authorization: token,
                    'Content-Type': 'multipart/form-data',
                },
            })
            .then(() => {
                // UIConfig.toast.success('Importuota');
                setUploading(false);
            })
            .catch((e) => {
                console.log('error', e);
                // UIConfig.toast.error('Klaida');
                setUploading(false);
            });
    };

    return (
        <Fragment>
            <ToolbarButtonWithMenu
                button={{
                    iconName: 'file-excel'
                    // title: t('Eksportuoti')
                }}
                menu={{
                    children: <Fragment>
                        {props.exports.map((ex) => {
                            return (
                                <MenuItem
                                    onClick={() => doDownload(ex)}
                                    key={`export-${ex.type}`}
                                >
                                    {ex.title}
                                </MenuItem>
                            );
                        })}
                    </Fragment>
                }}
            />

            {!!canUpload && <Fragment>
                <ToolbarButton
                    iconName='file-import'
                    onClick={() => {
                        if (ref && ref.current) {
                            // @ts-ignore
                            ref.current.click();
                        }
                    }}
                    title={t('Import')}
                />

                {/* @ts-ignore */}
                <input ref={ref} type={'file'} onChange={doUpload} style={{ display: 'none' }} />
            </Fragment>}


        </Fragment>
    );
}
