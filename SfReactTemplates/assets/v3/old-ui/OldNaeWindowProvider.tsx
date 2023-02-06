import React, { useContext, Fragment, useState, useEffect } from 'react'
import { useLocation } from '@newageerp/v3.templates.templates-core'

import { MainView, MainEdit } from '@newageerp/v3.bundles.app-bundle'


export interface NaeWindowProviderValue {
    showViewPopup: (props: MvcViewModalContentProps) => void
    closeViewPopup: () => void

    showEditPopup: (props: MvcEditModalContentProps) => void
    closeEditPopup: () => void

    viewProps: MvcViewModalContentProps
    editProps: MvcEditModalContentProps

    hasActivePopup: boolean,
}

const defviewProps: MvcViewModalContentProps = {
    id: 0,
    schema: '',
    popupId: ''
}

export const NaeWindowContext = React.createContext<NaeWindowProviderValue>({
    showViewPopup: () => { },
    closeViewPopup: () => { },

    showEditPopup: () => { },
    closeEditPopup: () => { },

    viewProps: defviewProps,
    editProps: defviewProps,

    hasActivePopup: false,
})

export const useNaeWindow = () => useContext(NaeWindowContext)

export interface NaeWindowProviderProps {
    children: any
}

export const NaeWindowProvider = ({ children }: NaeWindowProviderProps) => {
    let location = useLocation()

    useEffect(() => {
        if (viewProps !== null) {
            closeViewPopup()
        }
        if (editProps !== null) {
            closeEditPopup()
        }
    }, [location.pathname])

    const [viewProps, setViewProps] = useState<MvcViewModalContentProps | null>(
        null
    )
    const [editProps, setEditProps] = useState<MvcEditModalContentProps | null>(
        null
    )

    const showViewPopup = (props: MvcViewModalContentProps) => {
        setViewProps(props)
    }
    const closeViewPopup = () => {
        setViewProps(null)
    }

    const showEditPopup = (props: MvcEditModalContentProps) => {
        const _props: MvcEditModalContentProps = props.onSaveCallback
            ? props
            : { ...props, onSaveCallback: closeEditPopup }
        setEditProps(_props)
        return () => {
            setEditProps(null)
        }
    }
    const closeEditPopup = () => {
        setEditProps(null)
    }
    const closeEditPopupWithPrompt = () => {
        if (!window.confirm('Are you sure?')) return false;
        setEditProps(null)
    }

    return (
        <NaeWindowContext.Provider
            value={{
                showViewPopup,
                closeViewPopup,

                showEditPopup,
                closeEditPopup,

                viewProps: viewProps ? viewProps : defviewProps,
                editProps: editProps ? editProps : defviewProps,

                hasActivePopup: !!viewProps || !!editProps
            }}
        >
            <Fragment>
                {children}
                {!!viewProps && (
                    <Fragment>
                        <MainView
                            isPopup={true}
                            schema={viewProps.schema}
                            type={viewProps.type}
                            id={viewProps.id}
                            onBack={closeViewPopup}
                        />
                    </Fragment>
                )}
                {!!editProps && (
                    <Fragment>
                        <MainEdit
                            isPopup={true}
                            schema={editProps.schema}
                            type={(editProps.type ? editProps.type : 'main')}
                            id={editProps.id}
                            onBack={closeEditPopup}
                            onWindowClose={closeEditPopupWithPrompt}
                            onSaveCallback={editProps.onSaveCallback}
                            newStateOptions={editProps.newStateOptions}
                        />

                    </Fragment>
                )}
            </Fragment>
        </NaeWindowContext.Provider>
    )
}

// WINDOW
export interface MvcViewModalProps extends MvcViewModalContentProps {
    isOpen: boolean
    onClose: () => void
}

export interface MvcViewModalContentProps {
    schema: string
    id: string | number
    popupId?: string
    type?: string
}

export interface MvcEditModalProps extends MvcEditModalContentProps {
    isOpen: boolean
    onClose: () => void
}
export interface MvcEditModalContentProps {
    schema: string
    id: string | number
    newStateOptions?: any
    onSaveCallback?: (el: any, backFunc: any) => void
    parentElement?: any
    type?: string
    skipHiddenCheck?: boolean,
    fieldsToReturnOnSave?: string[],
}
