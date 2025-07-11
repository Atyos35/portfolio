const DeleteIcon = require('../../../../icons/deleteIcon.png');

type Props = {
    onClick: (e: React.MouseEvent<HTMLButtonElement>) => void;
};

export default function DeleteButton({ onClick }: Props) {
    return (
        <span
            onClick={onClick}
            className="ml-3 cursor-pointer"
            title="Supprimer"
        >
            <img 
                src={DeleteIcon} 
                alt="Supprimer" 
                className="w-5 h-5 min-w-[1.25rem] min-h-[1.25rem] align-middle" 
            />
        </span>
    );
}