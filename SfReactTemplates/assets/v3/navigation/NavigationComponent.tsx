import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { UI } from '@newageerp/nae-react-ui';
import React, { Fragment, useEffect, useState } from 'react'
import { useHistory } from 'react-router';
import { Popup } from '@newageerp/v3.popups.popup'
import { MailsForm } from '@newageerp/ui.mails.mails-form';

export default function NavigationComponent() {
    const history = useHistory();
    const { showViewPopup, showEditPopup } = UI.Window.useNaeWindow()
    const [emailOptions, setEmailOptions] = useState<any>(undefined);

    useEffect(() => {
        const eventListener = (e: any) => {
            const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : 'main'}/view/${e.detail.id}`;
            if (e.detail.replace) {
                history.replace(link);
            } else {
                history.push(link);
            }
        };
        window.addEventListener('SFSOpenMainWindow', eventListener);
        return () => {
            window.removeEventListener('SFSOpenMainWindow', eventListener);
        }
    }, []);

    useEffect(() => {
        const eventListener = (e: any) => {
            showViewPopup({
                id: e.detail.id,
                schema: e.detail.schema,
                type: e.detail.type ? e.detail.type : 'main',
            })
        };
        window.addEventListener('SFSOpenModalWindow', eventListener);
        return () => {
            window.removeEventListener('SFSOpenModalWindow', eventListener);
        }
    }, []);

    useEffect(() => {
        const eventListener = (e: any) => {
            const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : 'main'}/view/${e.detail.id}`;
            window.open(link, "_blank");
        };
        window.addEventListener('SFSOpenNewWindow', eventListener);
        return () => {
            window.removeEventListener('SFSOpenNewWindow', eventListener);
        }
    }, []);

    useEffect(() => {
        const eventListener = (e: any) => {
            const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : 'main'}/edit/${e.detail.id}`;
            window.open(link, "_blank");
        };
        window.addEventListener('SFSOpenEditNewWindow', eventListener);
        return () => {
            window.removeEventListener('SFSOpenEditNewWindow', eventListener);
        }
    }, []);

    useEffect(() => {
        const eventListener = (e: any) => {
            const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : 'main'}/edit/${e.detail.id}`;
            history.push(
                link,
                e.detail.options
            );
        };
        window.addEventListener('SFSOpenEditWindow', eventListener);
        return () => {
            window.removeEventListener('SFSOpenEditWindow', eventListener);
        }
    }, []);

    useEffect(() => {
        const eventListener = (e: any) => {
            showEditPopup({
                id: e.detail.id,
                schema: e.detail.schema,
                type: e.detail.type ? e.detail.type : 'main',
                newStateOptions: e.detail.options,
                onSaveCallback: e.detail.onSaveCallback,
                fieldsToReturnOnSave: e.detail.fieldsToReturnOnSave,
            })
        };
        window.addEventListener('SFSOpenEditModalWindow', eventListener);
        return () => {
            window.removeEventListener('SFSOpenEditModalWindow', eventListener);
        }
    }, []);

    useEffect(() => {
        const eventListener = (e: any) => {
            const link = `/u/${e.detail.schema}/${e.detail.type ? e.detail.type : 'main'}/list`;
            history.push(link);
        };
        window.addEventListener('SFSOpenListWindow', eventListener);
        return () => {
            window.removeEventListener('SFSOpenListWindow', eventListener);
        }
    }, []);

    useEffect(() => {
        const eventListener = (e: any) => {
            setEmailOptions(e.detail);
        };
        window.addEventListener('SFSOpenEmailForm', eventListener);
        return () => {
            window.removeEventListener('SFSOpenEmailForm', eventListener);
        }
    }, []);

    return (
        <Fragment>
            {!!emailOptions &&
                <Popup isPopup={true} onClick={() => setEmailOptions(undefined)} title="">
                    <MailsForm
                        onBack={() => setEmailOptions(undefined)}

                        {...emailOptions}
                        onSend={() => {
                            if (emailOptions.onSend) {
                                emailOptions.onSend();
                            }
                            setEmailOptions(undefined)
                        }}
                    />
                </Popup>
            }
        </Fragment>
    )
}
