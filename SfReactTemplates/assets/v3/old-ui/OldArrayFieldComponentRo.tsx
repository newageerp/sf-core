import React, { Fragment } from 'react'

import { defaultStrippedRowClassName } from './OldTrow'
import OldTbody from './OldTbody'

import Th from './OldTh'
import OldThead from './OldThead'
import OldTable from './OldTable'
import { useTranslation } from 'react-i18next';
import { getThColums, tdBody } from './OldArrayFieldComponent'
import { SFSOpenViewModalWindowProps } from '@newageerp/v3.popups.mvc-popup'
interface Props {
    schema: string
    title: string

    value: any[]

    tab: any
}

export default function OldArrayFieldComponentRo(props: Props) {
    const { t } = useTranslation();

    const bodyProps = {
        data: props.value,
        callback: (item: any, index: number) => {
            const scopes = item.scopes ? item.scopes : [];
            let rowClassName = "";

            scopes.forEach((scope: string) => {
                if (scope.indexOf('bg-row-color:') > -1) {
                    const scopeA = scope.split(":");
                    rowClassName = scopeA[1];
                }
            })

            return {
                columns: tdBody(item, props.tab, props.schema, (_schema, _id) =>
                    SFSOpenViewModalWindowProps({ schema: _schema, id: _id })
                ),
                className: rowClassName
            }
        }
    }

    return (
        <Fragment>
            <div>
                <div className={'tw3-space-y-4'}>
                    <OldTable

                        containerClassName={'tw3-w-full'}
                        thead={
                            <OldThead
                                columns={getThColums({ tab: props.tab, schema: props.schema })}
                                // extraContentEnd={<Th className={'text-right'}>{t('Veiksmai')}</Th>}
                            />
                        }
                        tbody={<OldTbody {...bodyProps} />}
                    />

                </div>
            </div>
        </Fragment>
    )
}
